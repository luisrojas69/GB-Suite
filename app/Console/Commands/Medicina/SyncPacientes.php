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
        $this->info('Iniciando sincronización de personal desde Profit...');

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

            $count = 0;

            foreach ($empleadosProfit as $emp) {
                $grupoSangre = trim($emp->grupo_sang);
                $tipo_sangre = ($grupoSangre === 'ND' || empty($grupoSangre)) ? null : $grupoSangre;
                $estaturaCm = ($emp->estatura > 0) ? (float)$emp->estatura * 100 : null;
                Paciente::updateOrCreate(
                    ['cod_emp' => trim($emp->cod_emp)],
                    [
                        'ci' => trim(str_replace(['-', '.'], '', $emp->ci)),
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
                        'discapacitado' => $emp->discapacitado,
                        'tipo_discapac' => $emp->tipo_discapac,
                        'avisar_a' => $emp->avisar_a,
                        'telf_contact' => $emp->telf_contact,
                        'dir_contact' => $emp->dir_contact,
                        'sueldo_mensual' => round((float)$emp->sueldo_mensual, 2),
                        'cantidad_hijos' => $emp->cantidad_hijos,
                        'es_zurdo' => $emp->zurdo ?? false,
                        'peso_inicial'   => round((float)$emp->peso, 2),
                        'talla_calzado' => $emp->talla_zap,
                        'talla_pantalon' => $emp->talla_pant,
                        'talla_camisa' => $emp->talla_camisa,
                        'estatura' => $estaturaCm,
                        'tipo_sangre' => $tipo_sangre,
                        'alergias' => $emp->alergico_a

                    ]
                );
                $count++;
            }

            // Marcar como 'L' a los que ya no están en nómina
            $codigosActivos = $empleadosProfit->pluck('cod_emp')->map(fn($item) => trim($item))->toArray();
            Paciente::whereNotIn('cod_emp', $codigosActivos)->update(['status' => 'L']);

            $this->info("Sincronización completada. Se procesaron {$count} registros.");

        } catch (\Exception $e) {
            $this->error("Error en la sincronización: " . $e->getMessage());
        }
    }


    public function handle_completo()
    {
        //Trae todos los trabajadores Incluyendo Egresados
        $this->info('Iniciando sincronización desde Profit Nómina...');

        // 1. Obtenemos los empleados desde la conexión sqlsrv_nomina
        $empleadosProfit = DB::connection('sqlsrv_nomina')
            ->table('snemple as e')
            ->join('sndepart as d', 'e.co_depart', '=', 'd.co_depart')
            ->join('sncargo as c', 'e.co_cargo', '=', 'c.co_cargo')
            ->select(
                'e.cod_emp', 'e.ci', 'e.nombre_completo', 'e.sexo', 'e.fecha_nac',
                'e.fecha_ing', 'e.status', 'e.direccion', 'e.telefono', 'e.edo_civ',
                'd.co_depart', 'd.des_depart', 'c.co_cargo', 'c.des_cargo',
                'e.discapacitado', 'e.tipo_discapac', 'e.co_cert', 'e.fecha_venc'
            )->get();

        foreach ($empleadosProfit as $emp) {
            Paciente::updateOrCreate(
                ['cod_emp' => trim($emp->cod_emp)],
                [
                    'ci' => trim(str_replace(['-', '.'], '', $emp->ci)),
                    'nombre_completo' => trim($emp->nombre_completo),
                    'sexo' => $emp->sexo,
                    'fecha_nac' => $emp->fecha_nac,
                    'fecha_ing' => $emp->fecha_ing,
                    'co_depart' => trim($emp->co_depart),
                    'des_depart' => trim($emp->des_depart),
                    'co_cargo' => trim($emp->co_cargo),
                    'des_cargo' => trim($emp->des_cargo),
                    'status' => trim($emp->status), // Limpia espacios como "A " o "V "
                    'discapacitado' => $emp->discapacitado,
                    'tipo_discapac' => $emp->tipo_discapac,
                    'co_cert' => $emp->co_cert,
                    'fecha_venc_cert' => $emp->fecha_venc,
                ]
            );
        }

        $this->info('Sincronización completada con éxito.');
    }
}