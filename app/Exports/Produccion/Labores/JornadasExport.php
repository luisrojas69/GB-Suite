<?php

namespace App\Exports\Produccion\Labores;

use App\Models\Produccion\Labores\LaborTablonDetalle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JornadasExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $filtros;

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        $query = LaborTablonDetalle::query()
            ->with([
                'registro.labor', 
                'registro.maquinarias.activo',
                'registro.contratista',
                'tablon.lote.sector'
            ]);

        // Aplicar filtros que vienen del Modal
        $query->whereHas('registro', function($q) {
            if ($this->filtros['desde'] && $this->filtros['hasta']) {
                $q->whereBetween('fecha_ejecucion', [$this->filtros['desde'], $this->filtros['hasta']]);
            }
            if ($this->filtros['labor_id']) {
                $q->where('labor_id', $this->filtros['labor_id']);
            }
        });

        if ($this->filtros['sector_id']) {
            $query->whereHas('tablon.lote', function($sq) {
                $sq->where('sector_id', $this->filtros['sector_id']);
            });
        }

        return $query->join('registro_labores', 'labor_tablon_detalle.registro_labor_id', '=', 'registro_labores.id')
                     ->orderBy('registro_labores.fecha_ejecucion', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID Jornada',
            'Fecha',
            'Sector',
            'Tablón',
            'Labor Realizada',
            'Modalidad',
            'Ejecutor (In-House / Contratista)',
            'Maquinaria (Horas)',
            'Hectáreas',
            'Observaciones'
        ];
    }

    public function map($detalle): array
    {
        $labor = $detalle->registro;
        $tablon = $detalle->tablon;

        $ejecutor = $labor->tipo_ejecutor === 'Propio' 
            ? 'Personal In-House' 
            : 'Outsourcing: ' . ($labor->contratista->nombre ?? $labor->contratista_nombre);

        $maquinarias = [];
        foreach ($labor->maquinarias as $maq) {
            $horas = $maq->horometro_final - $maq->horometro_inicial;
            $maquinarias[] = "{$maq->activo->codigo} ({$horas}h)";
        }
        $strMaq = empty($maquinarias) ? 'Labor Manual' : implode(' | ', $maquinarias);

        return [
            '#' . str_pad($labor->id, 5, '0', STR_PAD_LEFT),
            $labor->fecha_ejecucion->format('d/m/Y'),
            $tablon->lote->sector->nombre,
            $tablon->codigo_completo,
            $labor->labor->nombre,
            empty($maquinarias) ? 'Manual' : 'Mecanizada',
            $ejecutor,
            $strMaq,
            number_format($detalle->hectareas_logradas, 2),
            $labor->observaciones
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2D6A4F']]
            ],
        ];
    }
}