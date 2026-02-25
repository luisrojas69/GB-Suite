<?php

namespace App\Exports\Produccion\Agro;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PlanVsRealExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $filtros;

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        return DB::table('rol_molienda as p')
            // Unión para obtener nombres de Sector y Código de Tablón
            ->join('tablones as t', 'p.tablon_id', '=', 't.id')
            ->join('lotes as l', 't.lote_id', '=', 'l.id')
            ->join('sectores as s', 'l.sector_id', '=', 's.id')
            // Unión para obtener nombre de Variedad
            ->join('variedades as v', 'p.variedad_id', '=', 'v.id')
            // Unión con la tabla de ejecución real
            ->leftJoin('molienda_ejecutada as r', function($join) {
                $join->on('p.tablon_id', '=', 'r.tablon_id')
                     ->on('p.zafra_id', '=', 'r.zafra_id');
            })
            ->select([
                's.nombre as sector_nombre',
                't.codigo_tablon_interno as tablon_codigo',
                'v.nombre as variedad_nombre',
                'p.toneladas_estimadas',
                'r.toneladas_reales',
                'p.rendimiento_esperado',
                'r.rendimiento_real_avg',
                'p.fecha_corte_proyectada'
            ])
            ->where('p.zafra_id', $this->filtros['zafra_id'])
            ->when($this->filtros['sector_id'] !== 'todos', function($q) {
                return $q->where('s.id', $this->filtros['sector_id']);
            })
            ->orderBy('s.nombre', 'asc')
            ->orderBy('t.codigo_tablon_interno', 'asc');
    }

    public function headings(): array
    {
        return [
            'Sector / Hacienda',
            'Tablón',
            'Variedad',
            'Fecha Planificada',
            'Tons Proyectadas',
            'Tons Reales',
            'Diferencia (Tons)',
            '% Cumplimiento',
            'Rend. Plan (%)',
            'Rend. Real (%)'
        ];
    }

    public function map($fila): array
    {
        $real = $fila->toneladas_reales ?? 0;
        $diferencia = $real - $fila->toneladas_estimadas;
        $cumplimiento = $fila->toneladas_estimadas > 0 ? ($real / $fila->toneladas_estimadas) * 100 : 0;

        return [
            $fila->sector_nombre,
            $fila->tablon_codigo,
            $fila->variedad_nombre,
            $fila->fecha_corte_proyectada,
            number_format($fila->toneladas_estimadas, 2),
            number_format($real, 2),
            number_format($diferencia, 2),
            number_format($cumplimiento, 1) . '%',
            number_format($fila->rendimiento_esperado, 2),
            number_format($fila->rendimiento_real_avg ?? 0, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1B4332'] // Verde Granja Boraure
                ]
            ],
        ];
    }
}