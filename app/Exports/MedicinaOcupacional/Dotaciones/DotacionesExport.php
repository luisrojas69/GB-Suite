<?php

namespace App\Exports\MedicinaOcupacional\Dotaciones;

use App\Models\MedicinaOcupacional\Dotacion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class DotacionesExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $desde, $hasta, $motivo;

    public function __construct($desde, $hasta, $motivo) {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->motivo = $motivo;
    }

    public function query() {
        $query = Dotacion::query()->with('paciente')
            ->whereBetween('fecha_entrega', [$this->desde . ' 00:00:00', $this->hasta . ' 23:59:59']);

        if ($this->motivo !== 'todos') {
            $query->where('motivo', $this->motivo); 
        }

        return $query;
    }

    public function headings(): array {
        return [
        'Fecha', 
        'Ficha', 
        'Paciente', 
        'Cédula', 
        'Motivo', 
        'Talla Calzado', 
        'Codigo Calzado Profit', 
        'Calzado Asignado por SSL', 
        'Calzado Entregado en ALmacen',
        'Talla Pantalon', 
        'Codigo Pantalon Profit', 
        'Pantalon Asignado por SSL', 
        'Pantalon Entregado en ALmacen',
        'Talla Camisa', 
        'Codigo Camisa Profit', 
        'Camisa Asignada por SSL', 
        'Camisa Entregada en ALmacen',
        'Otros EPP',
        'Fecha de Despacho',
        'Observaciones',
        'Entregado Por'
    ];
    }

    public function map($dotacion): array {
        return [
            $dotacion->fecha_entrega ?? 'N/A',
            $dotacion->paciente->cod_emp ?? 'N/A',
            $dotacion->paciente->nombre_completo ?? 'N/A',
            $dotacion->paciente->ci ?? 'N/A',
            $dotacion->motivo ?? 'N/A',
            $dotacion->calzado_talla ?? 'N/A',
            $dotacion->co_art_calzado ?? 'N/A',
            $dotacion->calzado_entregado ?? 'N/A',
            $dotacion->entregado_en_almacen ?? 'N/A',
            $dotacion->pantalon_talla ?? 'N/A',
            $dotacion->co_art_pantalon ?? 'N/A',
            $dotacion->pantalon_entregado ?? 'N/A',
            $dotacion->entregado_en_almacen ?? 'N/A',
            $dotacion->camisa_talla ?? 'N/A',
            $dotacion->co_art_camisa ?? 'N/A',
            $dotacion->camisa_entregado ?? 'N/A',
            $dotacion->entregado_en_almacen ?? 'N/A',
            $dotacion->otros_epp_codigos ?? 'N/A',
            $dotacion->fecha_despacho_almacen ?? 'N/A',
            $dotacion->observaciones ?? 'N/A',
            $dotacion->user->name." ".$dotacion->user->last_name ?? 'N/A',
        ];
    }
}