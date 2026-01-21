<?php

namespace App\Exports\Produccion\Pluviometria;

use App\Models\Produccion\Pluviometria\RegistroPluviometrico;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PluviometriaPlanoExport implements FromCollection, WithHeadings, WithMapping
{
    protected $desde, $hasta;

    public function __construct($desde, $hasta) {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    /**
    * Traemos los datos crudos de la base de datos
    */
    public function collection()
    {
        return RegistroPluviometrico::with('sector')
                ->whereBetween('fecha', [$this->desde, $this->hasta])
                ->orderBy('fecha', 'asc')
                ->get();
    }

    /**
    * Encabezados de las columnas
    */
    public function headings(): array
    {
        return [
            'Fecha',
            'Sector',
            'Cantidad (mm)',
            'Intensidad',
            'Registrado por',
            'Fecha de Registro'
        ];
    }

    /**
    * Mapeo de cada fila (Transformamos IDs en nombres)
    */
    public function map($registro): array
    {
        return [
            Carbon::parse($registro->fecha)->format('d/m/Y'),
            $registro->sector->nombre,
            number_format($registro->cantidad_mm, 2),
            $registro->intensidad,
            $registro->usuario ? $registro->usuario->name." ".$registro->usuario->last_name : 'N/A',
            $registro->created_at->format('d/m/Y H:i')
        ];
    }
}