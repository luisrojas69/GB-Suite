@extends('layouts.app')
@section('title-page', isset($tablon->id) ? 'Editar Tablón: ' . $tablon->codigo_completo : 'Crear Nuevo Tablón')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
<style>
    /* ========================================
       VARIABLES Y TEMA AGRO PREMIUM
    ======================================== */
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-light: #d8f3dc;
        --agro-success: #52b788;
        --agro-warning: #f6c23e;
        --map-height: 750px;
    }

    /* HEADER */
    .edit-header {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 20px 25px; border-radius: 15px;
        margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* FORMULARIO ESTILIZADO */
    .agro-section {
        background: white; border-radius: 12px; padding: 20px;
        border: 1px solid #e3e6f0; margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }
    .agro-section-title {
        font-weight: 800; color: var(--agro-dark); text-transform: uppercase;
        font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 15px;
        border-bottom: 2px solid var(--agro-light); padding-bottom: 10px;
    }
    .form-label-custom { font-weight: 700; color: #5a5c69; font-size: 0.8rem; }
    .form-control-agro { border-radius: 8px; border: 1px solid #d1d3e2; background-color: #f8f9fc; }
    .form-control-agro:focus { border-color: var(--agro-success); background-color: #fff; box-shadow: 0 0 0 0.2rem rgba(82, 183, 136, 0.25); }

    /* CONTENEDOR DEL MAPA Y HUD FLOTANTE */
    .map-container {
        position: relative; border-radius: 15px; overflow: hidden;
        border: 3px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    #map { height: var(--map-height); width: 100%; z-index: 1; background: #e5e3df; }
    
    .map-hud {
        position: absolute; top: 15px; right: 15px; z-index: 1000;
        background: rgba(255, 255, 255, 0.95); padding: 15px 20px;
        border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        backdrop-filter: blur(5px); border-left: 5px solid var(--agro-success);
        min-width: 250px;
    }
    
    .digital-display {
        background: #111; color: #00ff00; font-family: 'Courier New', Courier, monospace;
        font-size: 1.5rem; font-weight: bold; padding: 5px 10px; border-radius: 5px;
        text-align: right; margin-top: 5px; letter-spacing: 2px;
    }

    /* BOTÓN DE ACCIÓN */
    .btn-save-agro {
        background: var(--agro-dark); color: white; border-radius: 10px;
        padding: 15px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; transition: all 0.3s; border: none;
    }
    .btn-save-agro:hover { background: #081c15; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(27, 67, 50, 0.3); color: white;}
</style>
@endpush

@section('content')
<div class="container-fluid mb-5">
    
    <div class="edit-header d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="bg-white p-2 rounded-circle mr-3 shadow-sm text-center" style="width: 45px; height: 45px;">
                <i class="fas fa-draw-polygon text-success fa-lg mt-1"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold">
                    {{ isset($tablon->id) ? 'Editor de Tablón: ' . $tablon->codigo_completo : 'Registrar Nuevo Tablón' }}
                </h4>
                <p class="mb-0 text-white-50 small">Configure la geometría y los datos agronómicos de la unidad productiva.</p>
            </div>
        </div>
        <a href="{{ route('produccion.areas.tablones.index') }}" class="btn btn-light btn-sm rounded-pill px-4 mt-3 mt-md-0 shadow-sm font-weight-bold text-dark">
            <i class="fas fa-arrow-left mr-1"></i> Volver al Catálogo
        </a>
    </div>

    @php
        // Definimos la ruta dinámicamente según si existe el ID o no
        $route = isset($tablon->id) ? route('produccion.areas.tablones.update', $tablon->id) : route('produccion.areas.tablones.store');
    @endphp

    <form action="{{ $route }}" method="POST" id="formTablon">
        @csrf
        @if(isset($tablon->id)) @method('PUT') @endif

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                
                <div class="agro-section">
                    <div class="agro-section-title"><i class="fas fa-map-marker-alt mr-2 text-primary"></i> 1. Ubicación y Nomenclatura</div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label-custom">Lote Padre (Contenedor) <span class="text-danger">*</span></label>
                        <select class="form-control form-control-agro @error('lote_id') is-invalid @enderror" name="lote_id" id="lote_id" required>
                            <option value="">-- Seleccione el Lote --</option>
                            @foreach ($lotes as $lote)
                                <option value="{{ $lote->id }}" 
                                    data-sector-nom="{{ $lote->sector->nombre }}"
                                    data-geometria="{{ $lote->sector->geometria_wkt ? \MatanYadaev\EloquentSpatial\Objects\Polygon::fromWkt($lote->sector->geometria_wkt)->toJson() : '' }}"
                                    {{ old('lote_id', $tablon->lote_id ?? '') == $lote->id ? 'selected' : '' }}>
                                    {{ $lote->sector->codigo_sector }} - {{ $lote->codigo_completo }} ({{ $lote->nombre }})
                                </option>
                            @endforeach
                        </select>
                        @error('lote_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label-custom">Código Interno <span class="text-danger">*</span></label>
                            <input type="text" name="codigo_tablon_interno" class="form-control form-control-agro text-center font-weight-bold text-primary @error('codigo_tablon_interno') is-invalid @enderror" value="{{ old('codigo_tablon_interno', $tablon->codigo_tablon_interno ?? '') }}" placeholder="Ej: 01" required>
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label-custom">Nombre / Alias <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control form-control-agro @error('nombre') is-invalid @enderror" value="{{ old('nombre', $tablon->nombre ?? '') }}" placeholder="Ej: Tablón Norte" required>
                        </div>
                    </div>
                </div>

                <div class="agro-section">
                    <div class="agro-section-title"><i class="fas fa-seedling mr-2 text-success"></i> 2. Parámetros Agronómicos</div>
                    
                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label-custom">Tipo de Ciclo</label>
                            <select class="form-control form-control-agro" name="tipo_ciclo">
                                <option value="Plantilla" {{ old('tipo_ciclo', $tablon->tipo_ciclo ?? '') == 'Plantilla' ? 'selected' : '' }}>Plantilla</option>
                                <option value="Soca" {{ old('tipo_ciclo', $tablon->tipo_ciclo ?? '') == 'Soca' ? 'selected' : '' }}>Soca</option>
                            </select>
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label-custom">Nro de Corte (Soca)</label>
                            <input type="number" name="numero_soca" class="form-control form-control-agro" value="{{ old('numero_soca', $tablon->numero_soca ?? 0) }}" min="0">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-custom">Variedad de Caña</label>
                        <select class="form-control form-control-agro" name="variedad_id">
                            <option value="">N/A (Sin sembrar)</option>
                            @foreach ($variedades as $id => $nombre)
                                <option value="{{ $id }}" {{ old('variedad_id', $tablon->variedad_id ?? '') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label-custom">Inicio de Ciclo</label>
                            <input type="date" name="fecha_inicio_ciclo" class="form-control form-control-agro" value="{{ old('fecha_inicio_ciclo', isset($tablon->fecha_inicio_ciclo) ? $tablon->fecha_inicio_ciclo->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label-custom">Meta (Ton/Ha)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="meta_ton_ha" class="form-control form-control-agro" value="{{ old('meta_ton_ha', $tablon->meta_ton_ha ?? '') }}">
                                <div class="input-group-append"><span class="input-group-text bg-light text-muted">T/Ha</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 form-group mb-0">
                            <label class="form-label-custom">Tipo de Suelo</label>
                            <input type="text" name="tipo_suelo" class="form-control form-control-agro" value="{{ old('tipo_suelo', $tablon->tipo_suelo ?? '') }}" placeholder="Arcilloso, etc.">
                        </div>
                        <div class="col-6 form-group mb-0">
                            <label class="form-label-custom">Estado Actual</label>
                            <select name="estado" class="form-control form-control-agro border-left-warning">
                                <option value="Preparacion" {{ old('estado', $tablon->estado ?? '') == 'Preparacion' ? 'selected' : '' }}>Preparación</option>
                                <option value="Crecimiento" {{ old('estado', $tablon->estado ?? '') == 'Crecimiento' ? 'selected' : '' }}>Crecimiento</option>
                                <option value="Maduro" {{ old('estado', $tablon->estado ?? '') == 'Maduro' ? 'selected' : '' }}>Maduro</option>
                                <option value="Cosecha" {{ old('estado', $tablon->estado ?? '') == 'Cosecha' ? 'selected' : '' }}>Cosecha</option>
                                <option value="Inactivo" {{ old('estado', $tablon->estado ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="agro-section pb-2">
                    <label class="form-label-custom">Notas u Observaciones</label>
                    <textarea name="descripcion" class="form-control form-control-agro mb-3" rows="2" placeholder="Detalles operativos...">{{ old('descripcion', $tablon->descripcion ?? '') }}</textarea>
                </div>

                {{-- Inputs Ocultos Críticos --}}
                <input type="hidden" name="geometria" id="geometria_input" value="{{ old('geometria', (isset($tablon->geometria_wkt) && $tablon->geometria_wkt) ? \MatanYadaev\EloquentSpatial\Objects\Polygon::fromWkt($tablon->geometria_wkt)->toJson() : '') }}">
                <input type="hidden" name="hectareas_documento" id="hectareas_documento" value="{{ old('hectareas_documento', $tablon->hectareas_documento ?? 0) }}">

                <button type="submit" class="btn btn-save-agro btn-block mb-4" id="btnSubmit">
                    <i class="fas fa-save mr-2"></i> {{ isset($tablon->id) ? 'Actualizar Tablón' : 'Guardar Tablón' }}
                </button>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="map-container">
                    
                    <div class="map-hud">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-satellite mr-1"></i> Área Poligonal Neta</div>
                        <div class="digital-display" id="displayArea">
                            {{ number_format(old('hectareas_documento', $tablon->hectareas_documento ?? 0), 2) }}
                        </div>
                        <div class="text-right text-muted small font-weight-bold mt-1">Hectáreas (Ha)</div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="font-weight-bold text-dark">Estatus GIS:</span>
                            <span id="geoStatus" class="{{ (isset($tablon->geometria_wkt) && $tablon->geometria_wkt) ? 'text-success' : 'text-danger' }} font-weight-bold">
                                {!! (isset($tablon->geometria_wkt) && $tablon->geometria_wkt) ? '<i class="fas fa-check-circle"></i> Trazado' : '<i class="fas fa-times-circle"></i> Pendiente' !!}
                            </span>
                        </div>
                    </div>

                    <div id="map"></div>
                    
                    <div class="bg-dark text-white p-2 d-flex justify-content-between align-items-center px-4" style="font-size: 0.85rem;">
                        <span><i class="fas fa-mouse-pointer text-info mr-2"></i> Utilice el panel lateral izquierdo del mapa para dibujar o editar polígonos.</span>
                        @error('geometria') <span class="text-danger font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Polígono Obligatorio</span> @enderror
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
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });

        // Inicialización Mapa Satelital
        const map = L.map('map').setView([9.960669, -70.234770], 14);
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        // Controles de Geoman (Solo permitir Polígonos)
        map.pm.addControls({
            position: 'topleft',
            drawMarker: false, drawCircleMarker: false, drawPolyline: false,
            drawCircle: false, drawRectangle: false,
            removalMode: true, editMode: true, dragMode: true
        });
        map.pm.setLang('es');

        let sectorLayer = null;
        let vecinosLayer = L.layerGroup().addTo(map);
        let myPolygonLayer = null; // Capa del tablón actual
        const lotesData = @json($lotes);

        // ==========================================
        // FUNCIONES DE GEOMETRÍA Y CÁLCULOS
        // ==========================================
        function updateAreaDisplay(layer) {
            const data = layer.toGeoJSON();
            const areaCalculada = (turf.area(data) / 10000).toFixed(2);
            
            // Actualizar UI
            $('#geometria_input').val(JSON.stringify(data.geometry));
            $('#hectareas_documento').val(areaCalculada);
            $('#displayArea').text(areaCalculada);
            $('#geoStatus').html('<i class="fas fa-check-circle"></i> Trazado').removeClass('text-danger').addClass('text-success');
        }

        // Cargar Polígono actual (Si existe)
        function loadCurrentPolygon() {
            const currentGeo = $('#geometria_input').val();
            if (currentGeo) {
                const geojson = JSON.parse(currentGeo);
                myPolygonLayer = L.geoJSON(geojson, {
                    style: { color: '#1cc88a', weight: 3, fillOpacity: 0.5, fillColor: '#1cc88a' }
                }).addTo(map);
                
                map.fitBounds(myPolygonLayer.getBounds(), { padding: [50, 50] });
                
                // Activar eventos de edición sobre este polígono
                myPolygonLayer.eachLayer(l => {
                    l.on('pm:edit pm:dragend', e => updateAreaDisplay(e.target));
                });
                
                // Ocultar botón de dibujar nuevo polígono (Solo 1 por tablón)
                $('.leaflet-pm-icon-polygon').parent().hide();
            }
        }

        // ==========================================
        // EVENTOS DEL MAPA Y GEOMAN
        // ==========================================
        map.on('pm:create', (e) => {
            myPolygonLayer = e.layer;
            
            // Estilizar el nuevo polígono
            myPolygonLayer.setStyle({ color: '#1cc88a', weight: 3, fillOpacity: 0.5, fillColor: '#1cc88a' });
            
            updateAreaDisplay(myPolygonLayer);
            Toast.fire({ icon: 'success', title: "Polígono registrado" });
            
            // Ocultar botón de crear
            $('.leaflet-pm-icon-polygon').parent().hide();

            myPolygonLayer.on('pm:edit pm:dragend', ev => updateAreaDisplay(ev.target));
        });

        map.on('pm:remove', (e) => {
            // Si el usuario borra la capa actual
            $('#geometria_input').val('');
            $('#hectareas_documento').val('0.00');
            $('#displayArea').text('0.00');
            $('#geoStatus').html('<i class="fas fa-times-circle"></i> Pendiente').removeClass('text-success').addClass('text-danger');
            
            // Volver a mostrar botón de dibujar
            $('.leaflet-pm-icon-polygon').parent().show();
            Toast.fire({ icon: 'warning', title: "Geometría eliminada" });
        });

        // ==========================================
        // EVENTO: Cambio de Lote (Carga contexto)
        // ==========================================
        $('#lote_id').on('change', function() {
            const selectedLoteId = $(this).val();
            const lote = lotesData.find(l => l.id == selectedLoteId);

            if (sectorLayer) map.removeLayer(sectorLayer);
            vecinosLayer.clearLayers(); 

            if (lote && lote.sector) {
                // Dibujar Borde del Sector
                const geoData = $(this).find('option:selected').data('geometria');
                if (geoData) {
                    sectorLayer = L.geoJSON(geoData, {
                        style: { color: '#f6c23e', weight: 4, dashArray: '10, 10', fillOpacity: 0 } // Borde amarillo, sin relleno
                    }).addTo(map);
                    
                    // Solo enfocar si NO hay polígono dibujado
                    if(!$('#geometria_input').val()) {
                        map.fitBounds(sectorLayer.getBounds());
                    }
                }

                // Dibujar Tablones Vecinos (Contexto)
                lote.sector.lotes.forEach(lh => {
                    lh.tablones.forEach(t => {
                        // Evitar redibujar el tablón actual
                        @if(isset($tablon->id)) if(t.id == {{ $tablon->id }}) return; @endif
                        
                        if (t.geometria_json) {
                            const geojson = JSON.parse(t.geometria_json);
                            L.geoJSON(geojson, {
                                style: { color: '#ffffff', weight: 1, fillOpacity: 0.35, fillColor: '#4e73df' } // Azul translúcido
                            })
                            .bindTooltip(t.codigo_completo + " (" + t.nombre + ")", { permanent: false, className: 'font-weight-bold' })
                            .addTo(vecinosLayer);
                        }
                    });
                });
            }
        });

        // ==========================================
        // INICIO AUTOMÁTICO
        // ==========================================
        loadCurrentPolygon();
        if($('#lote_id').val() != '') $('#lote_id').trigger('change');
        @if(session('success')) Toast.fire({ icon: 'success', title: '{{ session("success") }}' }); @endif
    });
</script>
@endpush