<?php

namespace App\Exports\Produccion\Arrimes\Fletes;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PreLiquidacionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $desde;
    protected $hasta;

    public function __construct($data, $desde, $hasta) {
        $this->data = $data;
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function collection() {
        return $this->data;
    }

    public function headings(): array {
        return [
            ['REPORTE DE PRE-LIQUIDACIÓN DE FLETES'],
            ['Periodo:', $this->desde . ' al ' . $this->hasta],
            [''],
            ['Contratista', 'Sector', 'Viajes', 'Toneladas Totales', 'Tarifa ($)', 'Monto a Pagar ($)']
        ];
    }

    /**
    * @var $fila
    */
    public function map($fila): array {
        return [
            $fila->contratista_nombre,
            $fila->sector_nombre,
            $fila->cantidad_viajes,
            number_format($fila->total_toneladas, 2, ',', '.'),
            number_format($fila->tarifa_flete, 2, ',', '.'),
            number_format($fila->monto_total, 2, ',', '.')
        ];
    }

    public function styles(Worksheet $sheet) {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2D6A4F']] // Color Agro
            ],
        ];
    }
}