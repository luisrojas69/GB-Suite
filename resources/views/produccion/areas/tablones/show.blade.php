@extends('layouts.app')
@section('title', 'Detalle de Tablón: ' . $tablon->nombre)

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔍 Detalle del Tablón: **{{ $tablon->nombre }}**</h1>
        <a href="{{ route('produccion.areas.tablones.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar al Listado
        </a>
    </div>

    @can('ver_sectores') {{-- Asumo permiso 'ver_sectores' --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Información del Tablón y Siembra</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Columna de Jerarquía --}}
                    <div class="col-md-4 border-right">
                        <h6>Jerarquía y Nomenclatura</h6>
                        <hr class="mt-0">
                        <p><strong>Código Único:</strong> <span class="badge badge-info">{{ $tablon->codigo_completo }}</span></p>
                        <p><strong>Código Interno:</strong> {{ $tablon->codigo_tablon_interno }}</p>
                        <p><strong>Lote Padre:</strong> {{ $tablon->lote->nombre }} ({{ $tablon->lote->codigo_completo }})</p>
                        <p><strong>Sector:</strong> {{ $tablon->lote->sector->nombre }}</p>
                        <p><strong>Finca:</strong> {{ $tablon->lote->sector->finca->nombre ?? 'N/A' }}</p> {{-- Asumiendo que Sector tiene relación con Finca --}}
                    </div>
                    
                    {{-- Columna de Datos Físicos y Metas --}}
                    <div class="col-md-4 border-right">
                        <h6>Área y Metas</h6>
                        <hr class="mt-0">
                        <p><strong>Área (Hectáreas Netas):</strong> <span class="badge badge-warning">{{ number_format($tablon->area_ha, 2, ',', '.') }} Ha</span></p>
                        <p><strong>Tipo de Suelo:</strong> {{ $tablon->tipo_suelo ?? 'N/A' }}</p>
                        <p><strong>Estado:</strong> <span class="badge badge-{{ ($tablon->estado == 'Activo') ? 'success' : (($tablon->estado == 'Preparacion') ? 'info' : 'danger') }}">{{ $tablon->estado }}</span></p>
                        <p><strong>Meta T/Ha Esperada:</strong> {{ $tablon->meta_ton_ha ? number_format($tablon->meta_ton_ha, 2, ',', '.') . ' T/Ha' : 'No Definida' }}</p>
                    </div>

                    {{-- Columna de Control de Siembra --}}
                    <div class="col-md-4">
                        <h6>Control de Caña (Siembra Actual)</h6>
                        <hr class="mt-0">
                        <p><strong>Variedad:</strong> <span class="badge badge-primary">{{ $tablon->variedad->nombre ?? 'Sin Siembra Asignada' }}</span></p>
                        <p><strong>Fecha de Siembra/Resoca:</strong> {{ $tablon->fecha_siembra ? $tablon->fecha_siembra->format('d/m/Y') : 'N/A' }}</p>
                        @if ($tablon->fecha_siembra)
                            <p><strong>Edad Actual:</strong> <span class="badge badge-secondary">{{ $tablon->fecha_siembra->diff(\Carbon\Carbon::now())->format('%y años, %m meses, %d días') }}</span></p>
                        @endif
                    </div>
                </div>
                
                <hr>

                <h5>Descripción / Notas</h5>
                <p>{{ $tablon->descripcion ?? 'N/A' }}</p>

                <div class="mt-4">
                    @can('editar_sectores')
                        <a href="{{ route('produccion.areas.tablones.edit', $tablon->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Tablón</a>
                    @endcan
                </div>
                
            </div>
        </div>
        
        <div class="card shadow mb-4 border-left-info">
            <div class="card-body">
                <p class="mb-0">Este tablón es la unidad base. Aquí se registrarán los movimientos de **Molienda/Cosecha**, lo que activará el control de **Soca** y el cálculo de **Rendimiento**.</p>
            </div>
        </div>

    @else
        <p class="alert alert-danger">Usted no tiene permisos para ver el detalle de este Tablón.</p>
    @endcan
</div>
@endsection