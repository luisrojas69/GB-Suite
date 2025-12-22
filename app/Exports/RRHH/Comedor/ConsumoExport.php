<?php

namespace App\Exports\RRHH\Comedor;

use App\Models\RRHH\Comedor\DiningRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsumoExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fecha_inicio;
    protected $fecha_fin;

    public function __construct($inicio, $fin)
    {
        $this->fecha_inicio = $inicio;
        $this->fecha_fin = $fin;
    }

    public function collection()
    {
        return DiningRecord::with(['mealType'])
            ->whereBetween('punch_time', [$this->fecha_inicio . ' 00:00:00', $this->fecha_fin . ' 23:59:59'])
            ->get();
    }

    public function headings(): array
    {
        return ['ID Biométrico', 'Empleado/Invitado', 'Servicio', 'Fecha y Hora', 'Costo', 'Origen'];
    }

    public function map($record): array
    {
        return [
            $record->employee_id,
            $record->employee_name ?? 'Usuario Biométrico',
            $record->mealType->name,
            $record->punch_time->format('d/m/Y h:i A'),
            $record->cost,
            $record->source,
        ];
    }
}