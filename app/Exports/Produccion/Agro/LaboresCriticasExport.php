<?php

namespace App\Exports\Produccion\Agro;

use App\Models\Produccion\Labores\RegistroLabor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LaboresCriticasExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $filtros;

    // Recibimos los filtros desde el controlador
    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

    // Usamos Query en lugar de Collection para no saturar la memoria RAM con miles de registros
    public function query()
    {
        $query = RegistroLabor::query()->with(['tablon.lote.sector', 'maquinaria', 'contratista']);

        if ($this->filtros['zafra_id']) {
            $query->where('zafra_id', $this->filtros['zafra_id']);
        }
        if ($this->filtros['sector_id'] !== 'todos') {
            $query->whereHas('tablon.lote', function($q) {
                $q->where('sector_id', $this->filtros['sector_id']);
            });
        }
        if ($this->filtros['desde'] && $this->filtros['hasta']) {
            $query->whereBetween('fecha_ejecucion', [$this->filtros['desde'], $this->filtros['hasta']]);
        }

        return $query->orderBy('fecha_ejecucion', 'desc');
    }

    // Definimos los encabezados del Excel
    public function headings(): array
    {
        return [
            'Fecha Labor',
            'Días Transcurridos (Ventana)',
            'Sector',
            'Tablón',
            'Tipo de Labor',
            'Ejecución (In-House / Outsourcing)',
            'Maquinaria / Equipo',
            'Horómetro Inicial',
            'Horómetro Final',
            'Horas Totales',
            'Estado'
        ];
    }

    // Mapeamos fila por fila cómo se va a pintar la información
    public function map($detalleTablon): array
    {
        $labor = $detalleTablon->registro;
        $tablon = $detalleTablon->tablon;

        // 1. CÁLCULO DE VENTANA CRÍTICA
        // Buscamos cuándo se cosechó este tablón en esta zafra
        $cosecha = \App\Models\Produccion\Arrimes\MoliendaEjecutada::where('tablon_id', $tablon->id)
                    ->where('zafra_id', $labor->zafra_id)
                    ->first();

        $diasTranscurridos = 'Sin cosecha reg.';
        $alerta = '';

        if ($cosecha && $cosecha->fecha_fin_cosecha) {
            $dias = Carbon::parse($cosecha->fecha_fin_cosecha)->diffInDays($labor->fecha_ejecucion);
            $meta = $labor->labor_catalogo->dias_meta_pos_cosecha;
            
            $diasTranscurridos = $dias . " días";
            if ($dias > $meta) {
                $alerta = " (¡FUERA DE VENTANA! Meta: {$meta}d)";
            }
        }

        // 2. CONSOLIDAR MAQUINARIA (Pueden ser varias)
        $maquinarias = $labor->maquinaria_detalles->map(function($m) {
            $horas = $m->horometro_final - $m->horometro_inicial;
            return "{$m->activo->codigo} ({$horas} hrs)";
        })->implode(' / ');

        return [
            $labor->fecha_ejecucion->format('d/m/Y'),
            $diasTranscurridos . $alerta,
            $tablon->lote->sector->nombre,
            $tablon->codigo,
            $labor->labor_catalogo->nombre,
            $labor->tipo_ejecutor === 'Propio' ? 'In-House' : 'Outsourcing: ' . ($labor->contratista->nombre ?? $labor->contratista_nombre),
            $maquinarias ?: 'Manual',
            $detalleTablon->hectareas_logradas,
            $labor->observaciones
        ];
    }

    // Dar estilo Premium al Excel (Cabeceras verdes y texto blanco)
    public function styles(Worksheet $sheet)
    {
        return [
            // Dar estilo a la Fila 1 (Los Encabezados)
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1B4332'] // Color Agro-Dark
                ]
            ],
        ];
    }
}