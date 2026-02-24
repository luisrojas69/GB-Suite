@extends('layouts.app')
@section('title', 'Crear Tablón')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
<style>
    #map { height: 600px; width: 100%; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .card-header-bg { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
    .form-group label { font-weight: bold; font-size: 0.85rem; color: #4e73df; }
    .invalid-feedback { font-size: 0.75rem; }
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
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0 text-gray-800">➕ Nuevo Tablón de Siembra</h1>
        <a href="{{ route('produccion.areas.tablones.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Cancelar y Volver
        </a>
    </div>

    {{-- ... (Mismos estilos y cabecera) ... --}}

<form action="{{ route('produccion.areas.tablones.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header py-2 card-header-bg">
                    <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Lote Padre (Ubicación) <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm @error('lote_id') is-invalid @enderror" name="lote_id" id="lote_id">
                                <option value="">Seleccione un Lote</option>
                                @foreach ($lotes as $lote)
                                    <option value="{{ $lote->id }}" 
                                        data-sector-nom="{{ $lote->sector->nombre }}"
                                        data-geometria="{{ $lote->sector->geometria_wkt ? \MatanYadaev\EloquentSpatial\Objects\Polygon::fromWkt($lote->sector->geometria_wkt)->toJson() : '' }}"
                                        {{ old('lote_id') == $lote->id ? 'selected' : '' }}>
                                        {{ $lote->sector->nombre }} - {{ $lote->nombre}} - ({{ $lote->codigo_completo}})
                                    </option>
                                @endforeach
                            </select>
                            @error('lote_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Cod. Interno <span class="text-danger">*</span></label>
                            <input type="text" name="codigo_tablon_interno" class="form-control form-control-sm @error('codigo_tablon_interno') is-invalid @enderror" value="{{ old('codigo_tablon_interno') }}" placeholder="Ej: 01">
                            @error('codigo_tablon_interno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hectáreas Netas <span class="text-danger">*</span> <span class="badge badge-warning">Autogenerado ⬠</span></label>
                            <input readonly type="number" step="0.01" name="hectareas_documento" id="hectareas_documento" class="form-control form-control-sm @error('hectareas_documento') is-invalid @enderror" value="{{ old('hectareas_documento') }}">

                            @error('hectareas_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label>Nombre del Tablón <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control form-control-sm @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Nombre descriptivo">
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-2">

                    {{-- NUEVA SECCIÓN: CONTROL DE CICLO --}}
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Tipo de Ciclo</label>
                            <select class="form-control form-control-sm" name="tipo_ciclo">
                                <option value="Plantilla" {{ old('tipo_ciclo') == 'Plantilla' ? 'selected' : '' }}>Plantilla</option>
                                <option value="Soca" {{ old('tipo_ciclo') == 'Soca' ? 'selected' : '' }}>Soca</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Nro Soca</label>
                            <input type="number" name="numero_soca" class="form-control form-control-sm" value="{{ old('numero_soca', 0) }}">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Variedad de Caña</label>
                            <select class="form-control form-control-sm" name="variedad_id">
                                <option value="">N/A (Sin sembrar)</option>
                                @foreach ($variedades as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('variedad_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Inicio de Ciclo (Quema/Siembra)</label>
                            <input type="date" name="fecha_inicio_ciclo" class="form-control form-control-sm" value="{{ old('fecha_inicio_ciclo') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Meta Ton/Ha</label>
                            <input type="number" step="0.01" name="meta_ton_ha" class="form-control form-control-sm" value="{{ old('meta_ton_ha') }}">
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Tipo de Suelo</label>
                            <input type="text" name="tipo_suelo" class="form-control form-control-sm" value="{{ old('tipo_suelo') }}" placeholder="Ej: Franco-Arcilloso">
                        </div>
                         <div class="form-group col-md-6">
                            <label>Estado Inicial</label>
                            <select name="estado" class="form-control form-control-sm @error('estado') is-invalid @enderror">
                                <option value="Preparacion" {{ old('estado') == 'Preparacion' ? 'selected' : '' }}>En Preparación</option>
                                <option value="Crecimiento" {{ old('estado') == 'Crecimiento' ? 'selected' : '' }}>En Crecimiento</option>
                                <option value="Maduro" {{ old('estado') == 'Maduro' ? 'selected' : '' }}>Maduro (Próximo a Cosecha)</option>
                                <option value="Cosecha" {{ old('estado') == 'Cosecha' ? 'selected' : '' }}>En Cosecha</option>
                                <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo / Descanso</option>
                            </select>
                            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label>Descripción / Notas</label>
                            <textarea name="descripcion" class="form-control form-control-sm" rows="2">{{ old('descripcion') }}</textarea>
                        </div>

                    </div>
                    {{-- ... (Botón y Mapa iguales) ... --}}

                        <button type="submit" class="btn btn-success btn-block mt-3 shadow">
                            <i class="fas fa-save"></i> Guardar Nuevo Tablón
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="alert alert-info py-2 small">
                    <i class="fas fa-info-circle"></i> Al seleccionar un lote, se mostrarán los límites del sector en el mapa para guiar su dibujo.
                </div>
                <div class="card shadow">
                    <div class="card-header py-2 card-header-bg d-flex justify-content-between align-items-center">
                        <span class="text-sm font-weight-bold text-dark">Dibujar Polígono del Terreno</span>
                        <span class="badge badge-info">Use la herramienta ⬠</span>
                    </div>
                    <div class="card-body p-1">
                        <div id="map"></div>
                        {{-- Campo oculto para la geometría --}}
                        <input type="hidden" name="geometria" id="geometria_input" value="{{ old('geometria') }}">
                        @error('geometria')
                            <div class="alert alert-danger mt-2 py-1 px-2 mb-0" style="font-size: 0.8rem;">
                                <i class="fas fa-exclamation-triangle"></i> Debe dibujar el área en el mapa.
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

<script>
    $(document).ready(function() {
        const map = L.map('map').setView([9.960669, -70.234770], 14);

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
        });

        
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        map.pm.addControls({
            position: 'topleft',
            drawMarker: false, drawCircleMarker: false, drawPolyline: false,
            drawRectangle: false, drawPolygon: true, removalMode: true, drawCircle: false, drawText: false, cutLayerMode: false
        });

        // Configuración de Snapping (Ajuste magnético)
        map.pm.setGlobalOptions({ 
            snappingOption: true, 
            snapDistance: 15,
            allowSelfIntersection: false,
            hintlineStyle: { color: '#3388ff', dashArray: '5,5' }
        });

        let sectorLayer = null;
        let vecinosLayer = L.layerGroup().addTo(map);
        const lotesData = @json($lotes);

        // EVENTO: Cambio de Lote (Carga geometría del Sector "Abuelo")
        $('#lote_id').on('change', function() {
            const selectedLoteId = $(this).val();
            const lote = lotesData.find(l => l.id == selectedLoteId);

            if (sectorLayer) map.removeLayer(sectorLayer);
            vecinosLayer.clearLayers(); // <--- LIMPIAR VECINOS ANTERIORES

            if (lote && lote.sector) {
                // 1. Dibujar Borde del Sector (Lo que ya tenías)
                const geoData = $(this).find('option:selected').data('geometria');
                const sectorNom = $(this).find('option:selected').data('sector-nom');

                if (geoData) {
                    sectorLayer = L.geoJSON(geoData, {
                        style: { color: '#4e73df', weight: 3, dashArray: '10, 10', fillOpacity: 0.05 }
                    }).addTo(map);
                    sectorLayer.bindTooltip("Sector: " + sectorNom);
                    map.fitBounds(sectorLayer.getBounds());
                }

                // 2. Dibujar Tablones Vecinos (NUEVO)
                lote.sector.lotes.forEach(lh => {
                    lh.tablones.forEach(t => {
                        if (t.geometria_json) {
                            const geojson = JSON.parse(t.geometria_json);
                            L.geoJSON(geojson, {
                                style: { color: '#858796', weight: 1, fillOpacity: 0.3, fillColor: '#858796' }
                            })
                            .bindTooltip("Ocupado por: " + t.nombre +"- ("+t.codigo_completo+")")
                            .addTo(vecinosLayer);
                        }
                    });
                });
            }
        });

        // Capturar dibujo y calcular hectáreas automáticas
        map.on('pm:create', (e) => {
            const layer = e.layer;
            const data = layer.toGeoJSON();
            $('#geometria_input').val(JSON.stringify(data.geometry));

            // Cálculo opcional de área con Turf.js para ayudar al usuario
            const area = turf.area(data); 
            const ha = (area / 10000).toFixed(2);
            $('#hectareas_documento').val(ha); 
            Toast.fire({ icon: 'success', title: "Área calculada: " + ha + " Ha" });

            layer.on('pm:edit pm:dragend', (ev) => {
                const updated = ev.target.toGeoJSON();
                $('#geometria_input').val(JSON.stringify(updated.geometry));
                const areaEdit = turf.area(updated);
                $('#hectareas_input').val((areaEdit / 10000).toFixed(2));
            });
        });

       map.on('pm:remove', () => $('#geometria_input').val(''));

        // Disparar el cambio si vuelve de un error de validación (old)
       if($('#lote_id').val() != '') $('#lote_id').trigger('change');
    });
</script>
@endpush