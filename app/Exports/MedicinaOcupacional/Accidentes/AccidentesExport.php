<?php

namespace App\Exports\MedicinaOcupacional\Accidentes;

use App\Models\MedicinaOcupacional\Accidente;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class AccidentesExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $desde, $hasta, $gravedad;

    public function __construct($desde, $hasta, $gravedad) {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->gravedad = $gravedad;
    }

    public function query() {
        $query = Accidente::query()->with('paciente')
            ->whereBetween('fecha_hora_accidente', [$this->desde . ' 00:00:00', $this->hasta . ' 23:59:59']);

        if ($this->gravedad !== 'todos') {
            // Ajusta 'motivo' según el nombre de tu columna
            $query->where('gravedad', $this->gravedad); 
        }

        return $query;
    }

    public function headings(): array {
        return ['Fecha', 'Ficha', 'Paciente', 'Cédula', 'Tipo Evento', 'Gravedad', 'Lugar Exacto', 'Investigador'];
    }

    public function map($consulta): array {
        return [
            $consulta->fecha_hora_accidente->format('d/m/Y H:i'),
            $consulta->paciente->cod_emp,
            $consulta->paciente->nombre_completo,
            $consulta->paciente->ci,
            $consulta->tipo_evento,
            $consulta->gravedad,
            $consulta->lugar_exacto,
            $consulta->user->name." ".$consulta->user->last_name ?? 'N/A',
        ];
    }
}