<?php

namespace App\Exports\MedicinaOcupacional\Pacientes;

use App\Models\MedicinaOcupacional\Paciente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PacientesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Solo exportamos los que no están eliminados (si usas SoftDeletes)
        return Paciente::whereIn('status', ['A', 'L'])->get();
    }

    public function headings(): array
    {
        return ['Ficha', 'Cédula', 'Nombre Completo', 'Departamento', 'Cargo', 'Estatus', 'Enfermedades'];
    }

    public function map($paciente): array
    {
        return [
            $paciente->cod_emp,
            $paciente->ci,
            $paciente->nombre_completo,
            $paciente->des_depart,
            $paciente->des_cargo,
            trim($paciente->status) == 'A' ? 'Activo' : 'Vacaciones',
            $paciente->enfermedades_base,
        ];
    }
}
