@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>

    /* Ajustar posición de los controles de zoom de Leaflet si es necesario */
    .leaflet-top.leaflet-left { top: 70px; } 
    
    #progreso-meta-container .card {
        background: rgba(255, 255, 255, 0.9); /* Un poco de transparencia para ver el mapa detrás */
        backdrop-filter: blur(4px);
    }
    .x-small { font-size: 0.7rem; }

    :root { --panel-width: 400px; }
    .main-wrapper { display: flex; height: calc(100vh - 100px); overflow: hidden; border-radius: 12px; shadow: 0 4px 15px rgba(0,0,0,0.1); }
    
    /* Mapa */
    #map-despacho { flex-grow: 1; height: 100%; z-index: 1; }
    
    /* Panel Derecho */
    .side-panel { 
        width: var(--panel-width); 
        background: #f8f9fc; 
        border-left: 1px solid #e3e6f0; 
        display: flex; 
        flex-direction: column; 
        z-index: 2; 
        overflow-y: auto;
    }

    .panel-section { padding: 20px; border-bottom: 1px solid #e3e6f0; background: white; margin-bottom: 10px; }
    .section-title { font-size: 0.75rem; font-weight: 800; color: #4e73df; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: flex; justify-content: space-between; }
    
    /* Estilos de Selección */
    .selected-tag { 
        display: inline-block; background: #4e73df; color: white; padding: 4px 10px; 
        border-radius: 50px; font-size: 0.8rem; margin: 2px; animation: pop 0.3s ease;
    }
    @keyframes pop { 0% { transform: scale(0.8); } 100% { transform: scale(1); } }

    /* Estilo de los Tractores */
    .maquina-card { background: #fff; border: 1px solid #d1d3e2; border-radius: 8px; padding: 12px; margin-bottom: 10px; position: relative; }
    .btn-remove { position: absolute; top: 5px; right: 5px; color: #e74a3b; cursor: pointer; }

    .sector-label {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid #4e73df;
        border-radius: 4px;
        padding: 2px 5px;
        font-weight: bold;
        color: #2e59d9;
        font-size: 10px;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
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
    <div class="main-wrapper shadow">
        
        <div id="map-despacho">
            <div id="progreso-meta-container" style="position: absolute; top: 10px; right: 10px; z-index: 1000; width: 180px;">
                <div class="card shadow-sm border-left-info">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Meta del Día</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800"><span id="pct-texto">0</span>%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div id="barra-progreso" class="progress-bar bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-1">
                                    <small class="text-muted"><span id="ha-actuales">0</span> / <input type="number" id="input-meta" value="50" style="width: 40px; border: none; background: transparent; font-weight: bold; color: #5a5c69;"> Ha</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>




        <div class="side-panel">
            <form action="{{ route('produccion.labores.store') }}" method="POST" id="formLabor">
                <input type="hidden" name="zafra_id" value="{{ $zafraActiva->id }}">
             @csrf
                <div class="panel-section">
                    <div class="section-title"><span>1. Definir Labor</span> <i class="fas fa-tools"></i></div>
                        <select name="labor_id" id="labor_id" class="form-control form-control-sm mb-2" required>
                            <option value="">Seleccione Labor...</option>
                            @foreach($labores as $l)
                                {{-- Usamos el boolean del modelo --}}
                                <option value="{{ $l->id }}" data-maquinaria="{{ $l->requiere_maquinaria }}">
                                    {{ $l->nombre }}
                                </option>
                            @endforeach
                        </select>
                    <input type="date" name="fecha" class="form-control form-control-sm mb-2" value="{{ date('Y-m-d') }}">
                    <textarea name="observaciones" placeholder="Observaciones" class="form-control form-control-sm" rows="1">{{ old('observaciones') }}</textarea>
                
                </div>

                <div class="panel-section">
                    <div class="section-title">
                        <span>2. Recurso Humano y Equipos</span>
                    </div>
                    <div class="btn-group btn-group-toggle btn-group-sm w-100 mb-3" data-toggle="buttons">
                        <label class="btn btn-outline-primary active" id="tipo_maquinaria" onclick="setTipoRecurso('maquinaria')">
                            <input type="radio" name="tipo_recurso" checked> <i class="fas fa-tractor"></i> Maquinaria
                        </label>
                        <label class="btn btn-outline-primary" id="tipo_manual" onclick="setTipoRecurso('manual')">
                            <input type="radio" name="tipo_recurso"> <i class="fas fa-hands-helping"></i> Manual
                        </label>
                    </div>

                    <div id="recurso-container">
                        </div>
                </div>
                <div class="panel-section">
                    <div class="panel-section">
                        <div class="section-title">
                            <span>3. Tablones Destino</span> 
                            <span class="badge badge-dark"><span id="total-ha-count">0.00</span> Ha Totales</span>
                        </div>
                        <div id="lista-tablones" class="d-flex flex-wrap">
                            <span class="text-muted small">Haga clic en los tablones del mapa...</span>
                        </div>
                    </div>

                </div>

                <div class="p-3">
                    <button type="submit" class="btn btn-success btn-block shadow">
                        <i class="fas fa-save"></i> REGISTRAR JORNADA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let selectedTablones = new Map(); 
    let map;
    let tablonesLayers = {};
    let metaDiaria = 50.00;

    // MEtas dinamicas segun labor
    const metasSugeridas = {
        '1': 12.0,  // Ejemplo: Cosecha 
        '2': 45.0,  // Ejemplo: Fertilización
        '3': 8.0,   // Ejemplo: Siembra
        '4': 100.0, // Ejemplo: Riego
        '5': 30.0   // Ejemplo: Herbicida
    };

    $(document).ready(function() {
        // Inicializar Mapa
        map = L.map('map-despacho').setView([9.960669, -70.234770], 14);

        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        const coloresEstado = {
            'Preparacion': '#36b9cc', 'Crecimiento': '#1cc88a',
            'Maduro': '#f6c23e', 'Cosecha': '#e74a3b', 'Inactivo': '#858796'
        };

        // 1. Renderizar bordes de SECTORES (Se hace primero para que queden debajo)


        @foreach($sectores as $sector)
            @if($sector->geometria_json)
                (function() {
                    const geoDataSector = {!! $sector->geometria_json !!};
                    const sectorLayer = L.geoJSON(geoDataSector, {
                        style: {
                            fillColor: 'transparent',
                            weight: 3,
                            color: '#4e73df', // Azul de contorno
                            opacity: 0.5,
                            dashArray: '10, 10', // Línea punteada para el sector
                            interactive: false
                        }
                    }).addTo(map);

                    sectorLayer.bindTooltip("{{ $sector->nombre }}", {
                        permanent: true, direction: 'center', className: 'sector-label'
                    });
                })();
            @endif
        @endforeach

        // 2. Renderizar TABLONES (Un solo loop optimizado)
        @foreach($sectores as $sector)
            @foreach($sector->lotes as $lote)
                @foreach($lote->tablones as $tablon)
                    @if($tablon->geometria_json)
                        (function() {
                            const tId = "{{ $tablon->id }}";
                            const tCodigo = "{{ $tablon->codigo_completo }}";
                            const tHectareas = {{ $tablon->hectareas_documento ?? 0 }};
                            const geoData = {!! $tablon->geometria_json !!};
                            const colorBase = coloresEstado["{{ $tablon->estado }}"] || '#858796';

                            const layer = L.geoJSON(geoData, {
                                style: { fillColor: colorBase, weight: 1, color: 'white', fillOpacity: 0.6 }
                            }).addTo(map);

                            tablonesLayers[tId] = layer;

                            layer.on('click', function() {
                                toggleTablon(tId, tCodigo, tHectareas);
                            });

                            layer.bindTooltip("Tablón: " + tCodigo + " (" + tHectareas + " Ha)");
                            
                            // Efecto visual al pasar el mouse
                            layer.on('mouseover', function() { this.setStyle({ fillOpacity: 0.9 }); });
                            layer.on('mouseout', function() { this.setStyle({ fillOpacity: 0.6 }); });
                        })();
                    @endif
                @endforeach
            @endforeach
        @endforeach
    });

    // --- FUNCIONES DE LÓGICA ---

    function toggleTablon(id, codigo, hectareas) {
        const layer = tablonesLayers[id];
        if (selectedTablones.has(id)) {
            selectedTablones.delete(id);
            if (layer) layer.setStyle({ color: 'white', weight: 1, dashArray: '' });
        } else {
            selectedTablones.set(id, { codigo: codigo, hectareas: hectareas });
            if (layer) layer.setStyle({ color: '#f6c23e', weight: 4, dashArray: '5, 5' });
        }
        renderTablonesTags();
    }

    function renderTablonesTags() {
        const container = $('#lista-tablones');
        container.empty();
        let totalHa = 0;

        if (selectedTablones.size === 0) {
            container.append('<span class="text-muted small">Seleccione tablones...</span>');
            updateTotalHectareas(0);
            return;
        }

        selectedTablones.forEach((datos, id) => {
            totalHa += parseFloat(datos.hectareas);
            container.append(`
                <span class="badge badge-primary p-2 m-1">
                    ${datos.codigo} (${datos.hectareas} Ha)
                    <i class="fas fa-times pointer ml-1" onclick="removerTablon('${id}')"></i>
                </span>
            `);
        });
        updateTotalHectareas(totalHa);
    }

    // Escuchar el cambio en el selector de labor
    $('#labor_id').on('change', function() {
        const selected = $(this).find(':selected');
        const requiereMaquinaria = selected.data('maquinaria'); // Esto recibirá 1 o 0
        const laborId = selected.val();
        const nuevaMeta = metasSugeridas[laborId] || 50; // Meta por defecto si no existe en el mapa
        
        // Actualizar la meta global y el valor del input en el mapa
        metaDiaria = nuevaMeta;
        $('#input-meta').val(nuevaMeta);
        renderTablonesTags(); // Recalcula la barra de progreso

        // Disparar SweetAlert pequeño para informar al usuario
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
        });
        Toast.fire({
            icon: 'info',
            title: `Meta ajustada a ${nuevaMeta} Ha para esta labor`
        });

        if (selected.data('maquinaria') !== undefined) {
            if (requiereMaquinaria == 1) {
                setTipoRecurso('maquinaria');
            } else {
                setTipoRecurso('manual');
            }

        }else{
            $('#recurso-container').hide();
        }

        

  
    });


    function updateTotalHectareas(total) {
        // 1. Actualizar contadores de texto
        $('#total-ha-count, #ha-actuales').text(total.toFixed(2));
        
        // 2. Calcular porcentaje y diferencia
        let porcentaje = (total / metaDiaria) * 100;
        let diferencia = metaDiaria - total;
        
        // 3. Ajustar color y ancho de la barra
        const barra = $('#barra-progreso');
        const pctTexto = $('#pct-texto');
        
        if (porcentaje >= 100) {
            barra.removeClass('bg-info').addClass('bg-success');
            pctTexto.removeClass('text-gray-800').addClass('text-success');
        } else {
            barra.removeClass('bg-success').addClass('bg-info');
            pctTexto.removeClass('text-success').addClass('text-gray-800');
        }
        
        barra.css('width', Math.min(porcentaje, 100) + '%');
        pctTexto.text(Math.round(porcentaje));

        // Opcional: Mostrar cuánto falta en el tooltip de la barra
        barra.attr('title', diferencia > 0 ? `Faltan ${diferencia.toFixed(2)} Ha` : '¡Meta cumplida!');
    }
    window.removerTablon = function(id) {
        toggleTablon(id, null, 0); 
    };

    $('#labor_id').on('change', function() {
        const metaData = $(this).find(':selected').data('meta');
        if (metaData) {
            metaDiaria = parseFloat(metaData);
            $('#input-meta').val(metaDiaria);
            renderTablonesTags();
        }
    });


    let tipoRecursoActual = 'maquinaria';


    function setTipoRecurso(tipo) {
        

        tipoRecursoActual = tipo;
        const container = $('#recurso-container');
        container.empty();
        
        if (tipo === 'maquinaria') {
            $('#tipo_manual').removeClass('active');
            $('#tipo_maquinaria').addClass('active');
            container.append(`
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="small font-weight-bold mb-0 text-primary">RECURSO MECANIZADO</label>

                <button type="button" class="btn btn-primary btn-circle"  onclick="addMaquinaria()"><i class="fa fa-plus"></i>

                </div>
                <div id="maquinaria-list"></div>
            `);
            addMaquinaria(); // Agregamos una fila por defecto
        } else {
            $('#tipo_maquinaria').removeClass('active');
            $('#tipo_manual').addClass('active');
            container.append(`
                <div class="card p-3 shadow-sm border-left-success bg-light">
                    <label class="small font-weight-bold text-success mb-2">RECURSO MANUAL</label>
                    <div class="form-group mb-2">
                        <label class="x-small font-weight-bold">ORIGEN DE PERSONAL</label>
                        <select name="origen_personal" id="origen_personal" class="form-control form-control-sm" onchange="toggleContratista(this.value)">
                            <option value="interno">Personal Interno (Nómina)</option>
                            <option value="outsourcing">Contratista / Outsourcing</option>
                        </select>
                    </div>
                    
                    <div id="div_contratista" class="form-group mb-2" style="display:none;">
                        <label class="x-small font-weight-bold">CONTRATISTA</label>
                        <input type="text" name="contratista_nombre" id="contratista_nombre" class="form-control form-control-sm" placeholder="Nombre de la Contratista">
                    </div>
                </div>
            `);
        }
    }

    function toggleContratista(val) {
        if (val === 'outsourcing') {
            $('#div_contratista').fadeIn();
            $('#contratista_nombre').prop("required", true);
        } else {
            $('#div_contratista').fadeOut();
            $('#contratista_nombre').prop("required", false);;
        }
    }
    // --- MAQUINARIA ---

    function addMaquinaria() {
        const id = Date.now();
        const html = `
            <div class="card mb-2 p-2 border-left-primary shadow-sm maquina-card" id="maq-${id}">
                <div class="d-flex justify-content-between mb-1">
                    <label class="small font-weight-bold mb-0">Equipo y Operador</label>
                    <i class="fas fa-times text-danger pointer" onclick="$('#maq-${id}').remove()"></i>
                </div>
                <select name="maquinarias[${id}][id]" class="form-control form-control-sm mb-1 selector-activo" required>
                    <option value="">Seleccione Equipo...</option>
                    @foreach($activos as $a) 
                        <option value="{{ $a->id }}" data-lectura="{{ $a->lectura_actual }}">{{ $a->codigo }} - {{ $a->nombre }}</option> 
                    @endforeach
                </select>
                <select name="maquinarias[${id}][operador_id]" class="form-control form-control-sm mb-1" required>
                    <option value="">Seleccione Operador...</option>
                    @foreach($operadores as $o) <option value="{{ $o->id }}">{{ $o->nombre_completo }}</option> @endforeach
                </select>
                <div class="row no-gutters mt-1">
                    <div class="col-6 pr-1">
                        <input type="number" step="0.01" name="maquinarias[${id}][h_ini]" class="form-control form-control-sm input-h-ini" placeholder="H. Inicial" required>
                    </div>
                    <div class="col-6 pl-1">
                        <input type="number" step="0.01" name="maquinarias[${id}][h_fin]" class="form-control form-control-sm input-h-fin" placeholder="H. Final" required>
                    </div>
                </div>
                <div class="error-msg text-danger x-small mt-1" style="display:none;"></div>
            </div>`;
        $('#maquinaria-list').append(html);
    }

    $(document).on('change', '.selector-activo', function() {
        const lectura = $(this).find(':selected').data('lectura');
        const card = $(this).closest('.maquina-card');
        if (lectura !== undefined) {
            card.find('.input-h-ini').val(lectura).attr('min', lectura);
            card.find('.input-h-fin').attr('min', lectura);
        }
    });

    $(document).on('input', '.input-h-ini, .input-h-fin', function() {
        const card = $(this).closest('.maquina-card');
        const ini = parseFloat(card.find('.input-h-ini').val()) || 0;
        const fin = parseFloat(card.find('.input-h-fin').val()) || 0;
        const errorMsg = card.find('.error-msg');
        
        if (fin > 0 && fin < ini) {
            card.addClass('border-left-danger').removeClass('border-left-primary');
            errorMsg.text('Horómetro final inválido').show();
        } else {
            card.addClass('border-left-primary').removeClass('border-left-danger');
            errorMsg.hide();
        }
    });

    $('#formLabor').on('submit', function(e) {
        $('.tablon-hidden-input').remove();
        if (selectedTablones.size === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: "Seleccione al menos un tablón" });
            return false;
        }
        // Validación según el tipo de recurso activo
        if (tipoRecursoActual === 'maquinaria') {
            if ($('.maquina-card').length === 0) {
                e.preventDefault();
                Swal.fire('Error', 'Debe agregar al menos un equipo para esta labor.', 'error');
                return false;
            }
        }
        
        selectedTablones.forEach((datos, id) => {
            $('<input>').attr({ type: 'hidden', name: 'tablon_ids[]', class: 'tablon-hidden-input', value: id }).appendTo('#formLabor');
        });
        return true;
    });


</script>
@endpush