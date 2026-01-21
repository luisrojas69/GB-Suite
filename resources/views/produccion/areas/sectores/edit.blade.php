@extends('layouts.app')
@section('title', 'Editar Sector')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
<style>
    #map { height: 500px; width: 100%; border-radius: 8px; }
    .label-sector {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid #4e73df;
        border-radius: 4px;
        padding: 2px 6px;
        font-weight: bold;
        color: #2e59d9;
        font-size: 11px;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
{{-- Mostrar mensajes de sesión --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Editar Sector: {{ $sector->nombre }}</h6>
                    <a href="{{ route('produccion.areas.sectores.index') }}" class="btn btn-sm btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('produccion.areas.sectores.update', $sector->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Código del Sector</label>
                            <input type="text" name="codigo_sector" class="form-control @error('codigo_sector') is-invalid @enderror" value="{{ old('codigo_sector', $sector->codigo_sector) }}">
                            @error('codigo_sector') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Nombre del Sector</label>
                            <input type="text" name="nombre" id="input_nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $sector->nombre) }}">
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $sector->descripcion) }}</textarea>
                        </div>
                        
                        {{-- Hidden para la geometría --}}
                         <input type="hidden" name="geometria" id="geometria_input" 
                               value="{{ ($sector->geometria_objeto) ? $sector->geometria_objeto->toJson() : '' }}">

                        <button type="submit" class="btn btn-primary btn-block shadow mt-4">
                            <i class="fas fa-sync"></i> Actualizar Sector
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Ubicación y Límites</h6>
                </div>
                <div class="card-body p-1">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
<script>
    $(document).ready(function() {
        const map = L.map('map').setView([9.960669, -70.234770], 15);
        
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        map.pm.addControls({
            position: 'topleft',
            drawMarker: false, drawPolyline: false, drawCircle: false, drawCircleMarker: false,
            cutLayer: false, rotateMode: false
        });

        // CARGAR POLÍGONO EXISTENTE USANDO EL OBJETO DINÁMICO
        @if($sector->geometria_objeto && method_exists($sector->geometria_objeto, 'toJson'))
            const geojsonData = {!! $sector->geometria_objeto->toJson() !!};
            const geoLayer = L.geoJSON(geojsonData).addTo(map);
            map.fitBounds(geoLayer.getBounds());

            // Al cargar, ya es editable
            geoLayer.eachLayer(layer => {
                // Escuchar tanto la edición de vértices como el movimiento del polígono
                layer.on('pm:edit pm:dragend', e => {
                    const data = e.target.toGeoJSON();
                    $('#geometria_input').val(JSON.stringify(data.geometry));
                });
            });
        @endif

        // Capturar creación de nuevos polígonos
        map.on('pm:create', (e) => {
            const data = e.layer.toGeoJSON();
            $('#geometria_input').val(JSON.stringify(data.geometry));
            
            e.layer.on('pm:edit', (ev) => {
                const updatedData = ev.target.toGeoJSON();
                $('#geometria_input').val(JSON.stringify(updatedData.geometry));
            });
        });

        // Limpiar input si se borra el polígono
        map.on('pm:remove', () => {
            $('#geometria_input').val('');
        });
    });
</script>
@endpush