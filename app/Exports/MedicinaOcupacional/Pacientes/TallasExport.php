<?php

namespace App\Exports\MedicinaOcupacional\Pacientes;

use App\Models\MedicinaOcupacional\Paciente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TallasExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Paciente::whereIn('status', ['A', 'L'])
            ->select('cod_emp', 'nombre_completo', 'talla_camisa', 'talla_pantalon', 'talla_calzado')
            ->get();
    }

    public function headings(): array
    {
        return ['Ficha', 'Empleado', 'Talla Camisa', 'Talla Pantalón', 'Calzado'];
    }

    public function map($paciente): array
    {
        return [
            $paciente->cod_emp,
            $paciente->nombre_completo,
            $paciente->talla_camisa ?? 'N/P',
            $paciente->talla_pantalon ?? 'N/P',
            $paciente->talla_calzado ?? 'N/P',
        ];
    }
}