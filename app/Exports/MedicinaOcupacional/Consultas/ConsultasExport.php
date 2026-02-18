<?php

namespace App\Exports\MedicinaOcupacional\Consultas;

use App\Models\MedicinaOcupacional\Consulta;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;


class ConsultasExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $desde, $hasta, $tipo;

    public function __construct($desde, $hasta, $tipo) {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->tipo = $tipo;
    }

    public function query() {
        $query = Consulta::query()->with('paciente')
            ->whereBetween('fecha_consulta', [$this->desde . ' 00:00:00', $this->hasta . ' 23:59:59']);

        if ($this->tipo !== 'todos') {
            // Ajusta 'motivo' según el nombre de tu columna
            $query->where('motivo_consulta', $this->tipo); 
        }

        return $query;
    }

    public function headings(): array {
        return ['Fecha', 'Ficha', 'Paciente', 'Cédula', 'Motivo', 'Diagnóstico CIE-10', 'Reposo (Días)', 'Médico'];
    }

    public function map($consulta): array {
        return [
            $consulta->created_at->format('d/m/Y H:i'),
            $consulta->paciente->cod_emp,
            $consulta->paciente->nombre_completo,
            $consulta->paciente->ci,
            $consulta->motivo_consulta,
            $consulta->diagnostico_cie10,
            $consulta->genera_reposo ? $consulta->dias_reposo : '0',
            "Dr. ".$consulta->medico->name." ".$consulta->medico->last_name ?? 'N/A',
        ];
    }
}