<?php

namespace App\Exports\Sistemas\Inventario;

use App\Models\Sistemas\Inventario\Item;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventarioExport implements FromQuery, WithHeadings, WithMapping
{
    protected $group;

    public function __construct($group)
    {
        $this->group = $group;
    }

    public function query()
    {
        $query = Item::with(['category', 'currentAssignment.assignable', 'currentAssignment.location']);

        if ($this->group === 'IT') {
            $query->where('item_group', 'IT');
        } elseif ($this->group === 'ADMIN') {
            $query->where('item_group', '!=', 'IT');
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID Activo',
            'Nombre/Descripción',
            'Categoría',
            'Marca',
            'Modelo',
            'Serial',
            'Tipo Activo',
            'Estado',
            'Responsable Actual',
            'Ubicación',
            'Fecha de Registro'
        ];
    }

    public function map($item): array
    {
        return [
            $item->asset_tag,
            $item->name,
            $item->category->nombre ?? 'N/A',
            $item->brand,
            $item->model,
            $item->serial,
            $item->item_group,
            ucfirst($item->status),
            $item->currentAssignment->assignable->nombre_completo ?? 'Sin asignar',
            $item->currentAssignment->location->nombre ?? 'N/A',
            $item->created_at->format('d-m-Y'),
        ];
    }
}