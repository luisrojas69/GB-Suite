
@extends('layouts.app')
@section('title-page', 'Agregar Nuevo Sector')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
<style>
    :root {
        --agro-primary: #1b4332;
        --agro-accent: #2d6a4f;
        --agro-success: #52b788;
        --map-height: 600px;
    }

    /* HEADER ESTILO AGRO-PREMIUM */
    .edit-header {
        background: linear-gradient(135deg, var(--agro-primary) 0%, var(--agro-accent) 100%);
        color: white; padding: 20px 25px; border-radius: 15px;
        margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* CONTENEDOR DE MAPA CON HERRAMIENTAS */
    .map-container {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        border: 2px solid #e3e6f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    #map { height: var(--map-height); width: 100%; z-index: 1; }

    /* FLOATING STATUS BAR EN EL MAPA */
    .map-status-overlay {
        position: absolute; top: 15px; right: 15px;
        z-index: 1000; background: rgba(255,255,255,0.95);
        padding: 12px 20px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        backdrop-filter: blur(5px);
        border-left: 5px solid var(--agro-success);
    }

    /* FORM STYLING */
    .agro-input-group {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
    }
    .agro-input-group:hover { border-color: var(--agro-success); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

    .form-label-custom {
        font-weight: 700; color: var(--agro-primary);
        text-transform: uppercase; font-size: 0.75rem;
        letter-spacing: 1px; display: block; margin-bottom: 8px;
    }

    .form-control-agro {
        border-radius: 8px; border: 1px solid #d1d3e2;
        padding: 12px 15px; font-size: 0.95rem;
    }
    .form-control-agro:focus {
        border-color: var(--agro-success);
        box-shadow: 0 0 0 0.2rem rgba(82, 183, 136, 0.25);
    }

    /* BOTONES */
    .btn-update-agro {
        background: var(--agro-primary); color: white;
        border-radius: 10px; padding: 15px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1px;
        transition: all 0.3s; border: none;
    }
    .btn-update-agro:hover {
        background: #081c15; color: #fff; transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(27, 67, 50, 0.3);
    }

    .info-badge {
        background: rgba(82, 183, 136, 0.1);
        color: var(--agro-accent); border-radius: 20px;
        padding: 4px 12px; font-size: 0.8rem; font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <div class="edit-header d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="icon-circle bg-white-50 mr-3">
                <i class="fas fa-map-marked-alt text-white"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold">Editor de Geometría del Sector</h4>
                <p class="mb-0 text-white-50">Modifique los límites y la información técnica del sector</p>
            </div>
        </div>
        <a href="{{ route('produccion.areas.sectores.index') }}" class="btn btn-light btn-sm rounded-pill px-4 mt-3 mt-md-0 shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver al Listado
        </a>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5 mb-4">
            <form action="{{ route('produccion.areas.sectores.store') }}" method="POST">
                @csrf
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-edit mr-2 text-success"></i> Nuevo Sector</h6>
                    </div>
                    <div class="card-body">
                        <div class="agro-input-group mb-3">
                            <label class="form-label-custom">Identificación del Sector</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="codigo_sector" class="form-control form-control-agro @error('codigo_sector') is-invalid @enderror" 
                                           placeholder="Código" value="{{ old('nombre') }}">
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="nombre" class="form-control form-control-agro @error('nombre') is-invalid @enderror" 
                                           placeholder="Nombre del Sector">
                                </div>
                            </div>
                            @error('codigo_sector') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="agro-input-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label-custom m-0">Superficie Calculada</label>
                                <span class="info-badge">Auto-GIS ⬠</span>
                            </div>
                            <div class="input-group">
                                <input readonly type="number" step="0.01" name="hectareas_geometria" id="hectareas_geometria" 
                                       class="form-control form-control-agro bg-white font-weight-bold text-primary" value="{{ old('hectareas_geometria') }}" 
                                      >
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white border-left-0 font-weight-bold">Has</span>
                                </div>
                            </div>
                        </div>

                        <div class="agro-input-group mb-4">
                            <label class="form-label-custom">Descripción y Observaciones</label>
                            <textarea name="descripcion" class="form-control form-control-agro" rows="4" 
                                      placeholder="Detalles adicionales sobre el terreno, accesos o limitaciones...">{{ old('descripcion') }}</textarea>
                        </div>

                        {{-- Input Oculto para JSON de Leaflet --}}
                        <input type="hidden" name="geometria" id="geometria_input">

                        <button type="submit" class="btn btn-update-agro btn-block shadow">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>

                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body py-3">
                        <h6 class="font-weight-bold small text-dark"><i class="fas fa-question-circle mr-1"></i> Ayuda de Edición:</h6>
                        <ul class="text-muted small mb-0 pl-3">
                            <li>Arrastre los <strong>puntos azules</strong> para cambiar vértices.</li>
                            <li>Haga clic en el icono de <strong>basura</strong> para eliminar y redibujar.</li>
                            <li>El cálculo de hectáreas se actualiza automáticamente al terminar la edición.</li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="map-container">
                <div class="map-status-overlay d-none d-md-block">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="text-xs text-uppercase text-muted font-weight-bold">Estado Geometría</div>
                            <div id="geo-status" class="font-weight-bold text-dark">
                                <i class="fas fa-check-circle text-success"></i> Polígono Cargado
                            </div>
                        </div>
                        <div class="border-left pl-3 ml-2">
                            <div class="text-xs text-uppercase text-muted font-weight-bold">Perímetro Aproximado</div>
                            <div id="perimetro-val" class="font-weight-bold text-primary">0.00 km</div>
                        </div>
                    </div>
                </div>

                <div id="map"></div>
                
                <div class="bg-dark text-white p-2 text-center small">
                    <i class="fas fa-mouse-pointer mr-2 text-info"></i> Use la barra de herramientas a la izquierda del mapa para manipular el dibujo.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
<script>
    $(document).ready(function() {
        
        // Configuración de Toast para notificaciones rápidas
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true
        });

        // Inicializar Mapa
        const map = L.map('map').setView([9.960669, -70.234770], 15);
        
        // Capa Satelital (Google Hybrid)
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        // Configuración de Geoman (Herramientas de dibujo)
        map.pm.addControls({
            position: 'topleft',
            drawMarker: false, 
            drawPolyline: false, 
            drawCircle: false, 
            drawCircleMarker: false,
            drawRectangle: false,
            cutLayer: false, 
            rotateMode: false,
            dragMode: true,
            editMode: true,
            removalMode: true
        });

        // Configuración de idioma Geoman
        map.pm.setLang('es');

        // Función para actualizar datos de hectáreas y perímetro
        function updateGeometryStats(layer) {
            const data = layer.toGeoJSON();
            const area = turf.area(data); 
            const has = (area / 10000).toFixed(2);
            
            // Perímetro
            const line = turf.length(data, {units: 'kilometers'});
            
            $('#hectareas_geometria').val(has);
            $('#perimetro-val').text(line.toFixed(2) + ' km');
            $('#geometria_input').val(JSON.stringify(data.geometry));
            $('#geo-status').html('<i class="fas fa-sync fa-spin text-info"></i> Sincronizando...');
            
            setTimeout(() => {
                $('#geo-status').html('<i class="fas fa-check-circle text-success"></i> Geometría Lista');
            }, 800);
        }

        // 2. ESCUCHAR CREACIÓN DE NUEVOS POLÍGONOS (Si se borró el anterior)
        map.on('pm:create', (e) => {
            const layer = e.layer;
            updateGeometryStats(layer);
            Toast.fire({ icon: 'success', title: "Nuevo polígono trazado" });

            layer.on('pm:edit pm:dragend', (ev) => {
                updateGeometryStats(ev.target);
            });
        });

        // 3. LIMPIAR DATOS SI SE ELIMINA EL POLÍGONO
        map.on('pm:remove', (e) => {
            $('#geometria_input').val('');
            $('#hectareas_geometria').val('0.00');
            $('#perimetro-val').text('0.00 km');
            $('#geo-status').html('<i class="fas fa-times-circle text-danger"></i> Sin Geometría');
            Toast.fire({ icon: 'warning', title: "Polígono eliminado" });
        });
    });
</script>
@endpush