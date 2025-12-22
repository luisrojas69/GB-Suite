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
        $this->info('Iniciando sincronización de personal activo desde Profit...');

        // 1. Obtenemos solo empleados con status 'A' (Activo) o 'V' (Vacaciones)
        $empleadosProfit = DB::connection('sqlsrv_nomina')
            ->table('snemple as e')
            ->join('sndepart as d', 'e.co_depart', '=', 'd.co_depart')
            ->join('sncargo as c', 'e.co_cargo', '=', 'c.co_cargo')
            ->select(
                'e.cod_emp', 'e.ci', 'e.nombre_completo', 'e.sexo', 'e.fecha_nac',
                'e.fecha_ing', 'e.status', 'e.direccion', 'e.telefono', 'e.edo_civ',
                'd.co_depart', 'd.des_depart', 'c.co_cargo', 'c.des_cargo',
                'e.discapacitado', 'e.tipo_discapac', 'e.co_cert', 'e.fecha_venc'
            )
            ->whereIn('e.status', ['A', 'V']) // <-- FILTRO CRÍTICO: Solo Activos y Vacaciones
            ->get();

        $count = 0;

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
            $count++;
        }

        // Opcional: Marcar como 'L' (Liquidado) en GB Suite a los que ya no vinieron en la consulta
        // Esto limpia tu lista local si alguien fue despedido en Profit
        $codigosActivos = $empleadosProfit->pluck('cod_emp')->map(fn($item) => trim($item))->toArray();
        Paciente::whereNotIn('cod_emp', $codigosActivos)->update(['status' => 'L']);

        $this->info("Sincronización completada. Se procesaron {$count} trabajadores activos.");
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