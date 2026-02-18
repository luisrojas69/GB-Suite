<?php

namespace App\Console\Commands\Medicina;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\MedicinaOcupacional\Paciente;

class SyncPacientes extends Command
{
    protected $signature = 'medicina:sync-pacientes';
    protected $description = 'Sincroniza empleados de Profit Nómina a GB Suite';

   public function handle()
    {
        $this->info('Iniciando sincronización inteligente...');
        
        try {
            $empleadosProfit = DB::connection('sqlsrv_nomina')
                ->table('snemple as e')
                ->join('sndepart as d', 'e.co_depart', '=', 'd.co_depart')
                ->join('sncargo as c', 'e.co_cargo', '=', 'c.co_cargo')
                ->select(
                    'e.cod_emp', 'e.ci', 'e.rif', 'e.lugar_nac', 'e.estado', 'e.municipio', 
                    'e.parroquia', 'e.nac', 'e.correo_e', 'e.telefono', 'e.nombre_completo', 
                    'e.sexo', 'e.fecha_nac', 'e.avisar_a', 'e.telf_contact', 'e.dir_contact',
                    'e.fecha_ing', 'e.status', 'e.direccion', 'e.edo_civ',
                    'd.co_depart', 'd.des_depart', 'c.co_cargo', 'c.des_cargo',
                    'e.discapacitado', 'e.tipo_discapac', 'e.co_cert', 'e.fecha_venc', // Aquí faltaba la coma
                    'e.zurdo','e.peso','e.estatura', 'e.talla_zap', 'e.talla_pant', 
                    'e.talla_camisa', 'e.grupo_sang', 'e.profesion','e.nivel_acad','e.alergico_a',
                    DB::raw("(SELECT COUNT(*) FROM sngru_fa AS f WHERE f.cod_emp = e.cod_emp AND f.tip_gru_fa = 'HI') as cantidad_hijos"),
                    DB::raw("ISNULL((SELECT eva.val_n FROM snem_va AS eva WHERE eva.cod_emp = e.cod_emp AND eva.co_var = (SELECT co_var FROM snasi_co WHERE codigo = 'A001')), 0) as sueldo_mensual")
                )
                ->get();

            foreach ($empleadosProfit as $emp) {
                $codEmp = trim($emp->cod_emp);
                $paciente = Paciente::where('cod_emp', $codEmp)->first();

                // Preparar datos de IDENTIDAD (Siempre se actualizan)
                $datosIdentidad = [
                        'ci' => substr(trim(str_replace(['-', '.'], '', $emp->ci)), 0, 20), //hacer esto con todos 
                        'rif' => trim(str_replace(['-', '.'], '', $emp->rif)),
                        'lugar_nac' => $emp->lugar_nac,
                        'estado' => $emp->estado,
                        'municipio' => $emp->municipio,
                        'parroquia' => $emp->parroquia,
                        'edo_civ' => substr(trim($emp->edo_civ), 0, 1),
                        'nombre_completo' => trim($emp->nombre_completo),
                        'sexo' => substr(trim($emp->sexo), 0, 1),
                        'profesion' => $emp->profesion,
                        'nivel_acad' => $emp->nivel_acad,
                        'nac' => $emp->nac,
                        'correo_e' => $emp->correo_e,
                        'telefono' => $emp->telefono,
                        'fecha_nac' => $emp->fecha_nac,
                        'fecha_ing' => $emp->fecha_ing,
                        'co_depart' => trim($emp->co_depart),
                        'des_depart' => trim($emp->des_depart),
                        'co_cargo' => trim($emp->co_cargo),
                        'des_cargo' => trim($emp->des_cargo),
                        'status' => substr(trim($emp->status), 0, 1),
                        'avisar_a' => $emp->avisar_a,
                        'telf_contact' => $emp->telf_contact,
                        'dir_contact' => $emp->dir_contact,
                        'sueldo_mensual' => round((float)$emp->sueldo_mensual, 2),
                        'cantidad_hijos' => $emp->cantidad_hijos,

                ];

                if (!$paciente) {
                    // CASO 1: NO EXISTE - Semilla inicial completa
                    $this->line("Creando nuevo paciente: {$codEmp}");

                    $grupoSangre = trim($emp->grupo_sang);
                    $tipo_sangre = ($grupoSangre === 'ND' || empty($grupoSangre)) ? null : $grupoSangre;
                    $estaturaCm = ($emp->estatura > 0) ? (float)$emp->estatura * 100 : null;
                    
                    $datosClinicosIniciales = [
                        'cod_emp' => $codEmp,
                        'peso_inicial' => round((float)$emp->peso, 2),
                        'estatura' => $estaturaCm,
                        'tipo_sangre' => $tipo_sangre,
                        'alergias' => $emp->alergico_a,
                        'talla_calzado' => $emp->talla_zap,
                        'talla_pantalon' => $emp->talla_pant,
                        'talla_camisa' => $emp->talla_camisa,
                        'discapacitado' => $emp->discapacitado,
                        'tipo_discapac' => $emp->tipo_discapac,
                        'es_zurdo' => $emp->zurdo ?? false,
                        'validado_medico' => false // Por defecto
                    ];
                    
                    Paciente::create(array_merge($datosIdentidad, $datosClinicosIniciales));
                    
                } else {
                    // CASO 2: YA EXISTE - Actualización selectiva
                    
                    // Si el médico YA validó, solo actualizamos identidad
                    if ($paciente->validado_medico) {
                        $this->line("Actualizando datos de identidad de empleado: {$codEmp}");
                        $paciente->update($datosIdentidad);
                    } else {
                        // Si NO ha sido validado, actualizamos identidad + huecos clínicos
                        $this->line("Actualizando datos de identidad y medicos del empleado: {$codEmp}");
                        $datosUpdate = $datosIdentidad;

                        // Lógica de "Llenar solo si está vacío en GbSuite"
                        if (empty($paciente->tipo_sangre)) {
                            $grupo = trim($emp->grupo_sang);
                            $datosUpdate['tipo_sangre'] = ($grupo !== 'ND' && !empty($grupo)) ? $grupo : null;
                        }
                        
                        if (empty($paciente->peso_inicial)) {
                            $datosUpdate['peso_inicial'] = round((float)$emp->peso, 2);
                        }

                        if (empty($paciente->estatura)) {
                            $estaturaCm = ($emp->estatura > 0) ? (float)$emp->estatura * 100 : null;
                            $datosUpdate['estatura'] = $estaturaCm;
                        }

                        if (empty($paciente->alergias)) {
                            $datosUpdate['alergias'] = $emp->alergico_a;
                        }

                        if (empty($paciente->es_zurdo)) {
                           $datosUpdate['es_zurdo'] = $emp->zurdo ?? false;
                        }

                        if (empty($paciente->discapacitado)) {
                           $datosUpdate['discapacitado'] = $emp->discapacitado;
                        }

                        if (empty($paciente->tipo_discapac)) {
                           $datosUpdate['tipo_discapac'] = $emp->tipo_discapac;
                        }

                        //Tallas
                        if (empty($paciente->talla_calzado)) {
                           $datosUpdate['talla_calzado'] = $emp->talla_zap;
                        }

                        if (empty($paciente->talla_pantalon)) {
                           $datosUpdate['talla_pantalon'] = $emp->talla_pant;
                        }

                        if (empty($paciente->talla_camisa)) {
                           $datosUpdate['talla_camisa'] = $emp->talla_camisa;
                        }

                        
                        $paciente->update($datosUpdate);
                    }
                }
            }

            // Marcar bajas (Estatus 'L' para los que no vinieron en el fetch)
            $codigosActivos = $empleadosProfit->pluck('cod_emp')->map(fn($item) => trim($item))->toArray();
            Paciente::whereNotIn('cod_emp', $codigosActivos)->update(['status' => 'L']);

            $this->info("Sincronización finalizada.");

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}