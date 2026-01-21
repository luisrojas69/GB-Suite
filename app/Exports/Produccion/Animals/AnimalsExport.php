<?php

namespace App\Exports\Produccion\Animals;

use App\Models\Produccion\Animales\Animal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnimalsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Traemos las relaciones para evitar el problema N+1
        return Animal::with(['specie', 'category', 'location', 'owner', 'latestWeighing'])->get();
    }

    /**
    * Encabezados de las columnas
    */
    public function headings(): array
    {
        return [
            'ID Sistema',
            'Código/Arete',
            //'Nombre',
            'Especie',
            'Categoría',
            'Sexo',
            'Fecha Ingreso',
            'Ubicación/Lote',
            'Último Peso (Kg)', // Nueva columna
            'Fecha Pesaje',     // Nueva columna
            'Propietario',
            'CeCo (Contable)', // El Centro de Costo de la Categoría
            'Estado'
        ];
    }

    /**
    * Mapeo de cada fila
    */
    public function map($animal): array
    {
        return [
            $animal->id,
            $animal->iron_id,
           // $animal->name,
            $animal->specie->name ?? 'N/A',
            $animal->category->name ?? 'N/A',
            $animal->sex ?? 'N/A',
            $animal->birth_date->format('d/m/Y'),
            $animal->location->name ?? 'N/A',
            // Si tiene pesaje muestra el peso, si no, 'N/A'
            $animal->latestWeighing->weight_kg ?? 'Sin registro',
            // Si tiene pesaje muestra la fecha formateada
            $animal->latestWeighing ? $animal->latestWeighing->weighing_date->format('d/m/Y') : 'N/A',
            $animal->owner->name ?? 'N/A',
            $animal->category->cost_center_id ?? 'Sin Asignar',
            $animal->is_active ? 'Activo' : 'Baja'
        ];
    }
}