<?php

namespace App\Exports\Produccion\Pozo;

use App\Models\Produccion\Pozo\Activo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ActivoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Traemos todos los registros con sus relaciones
        return Activo::with('pozoAsociado')->get();
    }

    /**
    * Definir los encabezados del Excel
    */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Activo',
            'Ubicación',
            'Coordenadas',
            'Tipo de Activo',
            'Subtipo (Si es Pozo)',
            'Pozo Asociado',
            'Estatus Actual',
            'Último Cambio de Estatus',
        ];
    }

    /**
    * Mapear los datos para cada fila
    */
    public function map($activo): array
    {
        return [
            $activo->id,
            $activo->nombre,
            $activo->ubicacion,
            $activo->coordenadas,
            $activo->tipo_activo,
            $activo->subtipo_pozo ?? 'N/A',
            $activo->pozoAsociado ? $activo->pozoAsociado->nombre : 'N/A',
            $activo->estatus_actual,
            $activo->fecha_ultimo_cambio ? $activo->fecha_ultimo_cambio->format('d/m/Y H:i') : 'N/A',
        ];
    }
}