@extends('layouts.app')
@section('title-page', 'Despacho de Labores de Campo')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    /* VARIABLES AGRO PREMIUM */
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-accent: #52b788;
        --panel-width: 420px;
    }

    .main-wrapper { 
        display: flex; height: calc(100vh - 120px); overflow: hidden; 
        border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        background: white; border: 1px solid #e3e6f0;
    }
    
    #map-despacho { flex-grow: 1; height: 100%; z-index: 1; position: relative; }
    
    /* Panel Lateral Premium */
    .side-panel { 
        width: var(--panel-width); background: #fdfdfd; 
        border-left: 1px solid #e3e6f0; display: flex; 
        flex-direction: column; z-index: 2; overflow-y: auto;
    }

    .panel-header {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 15px 20px; text-align: center; font-weight: bold;
    }

    .panel-section { padding: 15px 20px; border-bottom: 1px solid #f1f1f1; }
    .section-title { font-size: 0.75rem; font-weight: 800; color: var(--agro-primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
    
    /* Estilos del Mapa y Widget */
    #progreso-meta-container .card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(5px); border: none; border-radius: 8px; }
    .leaflet-top.leaflet-left { top: 15px; } 

    /* Tags y Cards */
    .badge-tablon { background: rgba(45, 106, 79, 0.1); color: var(--agro-primary); border: 1px solid rgba(45, 106, 79, 0.3); font-size: 0.8rem; padding: 6px 10px; margin: 3px; }
    .maquina-card { background: white; border: 1px solid #e3e6f0; border-left: 4px solid var(--agro-accent); border-radius: 8px; padding: 12px; margin-bottom: 10px; position: relative; transition: all 0.2s ease;}
    .maquina-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .btn-remove-row { position: absolute; top: 10px; right: 10px; color: #e74a3b; cursor: pointer; opacity: 0.7; }
    .btn-remove-row:hover { opacity: 1; }

    /* Tooltips del mapa */
    .sector-label { background: rgba(255, 255, 255, 0.8); border: 1px solid var(--agro-primary); border-radius: 4px; padding: 2px 6px; font-weight: bold; color: var(--agro-dark); font-size: 10px; }
</style>
@endpush

@section('content')
<div class="container-fluid pb-3">
    @if (session('success')) <div class="alert alert-success shadow-sm"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger shadow-sm"><i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}</div> @endif

    <div class="main-wrapper">
        <div id="map-despacho">
            <div id="progreso-meta-container" style="position: absolute; top: 15px; right: 15px; z-index: 1000; width: 220px;">
                <div class="card shadow-sm border-left-success">
                    <div class="card-body p-3">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><i class="fas fa-bullseye mr-1"></i> Meta Diaria (Ha)</div>
                        <div class="row no-gutters align-items-center mb-2">
                            <div class="col-auto mr-3 h5 mb-0 font-weight-bold text-gray-800"><span id="pct-texto">0</span>%</div>
                            <div class="col">
                                <div class="progress progress-sm"><div id="barra-progreso" class="progress-bar bg-success" style="width: 0%"></div></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-sm">
                            <span class="font-weight-bold text-dark" id="ha-actuales">0.00</span>
                            <span class="text-muted">/</span>
                            <input type="number" id="input-meta" value="50" class="form-control form-control-sm text-center font-weight-bold text-primary" style="width: 70px; border: 1px dashed #ccc;">
                        </div>
                    </div>
                </div>
            </div>           
        </div>

        <div class="side-panel">
            <div class="panel-header">
                <i class="fas fa-clipboard-list mr-1"></i> Formulario de Despacho
            </div>

            <form action="{{ route('produccion.labores.store') }}" method="POST" id="formLabor">
                @csrf
                <input type="hidden" name="zafra_id" value="{{ $zafraActiva->id }}">
                <input type="hidden" name="tipo_recurso" id="input_tipo_recurso" value="maquinaria">

                <div class="panel-section bg-light">
                    <div class="section-title"><span>1. Datos de la Labor</span></div>
                    <div class="form-group mb-2">
                        <select name="labor_id" id="labor_id" class="form-control select2-agro" required>
                            <option value="">Seleccione Labor...</option>
                            @foreach($labores as $l)
                                <option value="{{ $l->id }}" data-maquinaria="{{ $l->requiere_maquinaria }}">
                                    {{ $l->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 pr-1">
                            <label class="small text-muted mb-0">Fecha de Ejecución</label>
                            <input type="date" name="fecha" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6 pl-1">
                            <label class="small text-muted mb-0">Observaciones</label>
                            <input type="text" name="observaciones" class="form-control form-control-sm" placeholder="Opcional...">
                        </div>
                    </div>
                </div>

                <div class="panel-section">
                    <div class="section-title"><span>2. Asignación de Recursos</span></div>
                    
                    <div class="btn-group btn-group-toggle w-100 mb-3 shadow-sm" data-toggle="buttons">
                        <label class="btn btn-outline-success active" id="btn_maquinaria" onclick="toggleRecursoView('maquinaria')">
                            <input type="radio" autocomplete="off" checked> <i class="fas fa-tractor"></i> Mecanizada
                        </label>
                        <label class="btn btn-outline-success" id="btn_manual" onclick="toggleRecursoView('manual')">
                            <input type="radio" autocomplete="off"> <i class="fas fa-hands-helping"></i> Manual
                        </label>
                    </div>

                    <div id="block_maquinaria">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small font-weight-bold text-success">Equipos Asignados</span>
                            <button type="button" class="btn btn-sm btn-success rounded-circle" onclick="addMaquinariaRow()"><i class="fas fa-plus"></i></button>
                        </div>
                        <div id="maquinaria_wrapper">
                            </div>
                    </div>

                    <div id="block_manual" style="display: none;">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-dark">Origen del Personal</label>
                                    <select name="origen_personal" id="origen_personal" class="form-control form-control-sm" onchange="toggleContratista()">
                                        <option value="interno">Personal In-House (Nómina)</option>
                                        <option value="outsourcing">Contratista / Outsourcing</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="div_contratista" style="display:none;">
                                    <label class="small font-weight-bold text-dark">Empresa Contratista</label>
                                    <select name="contratista_id" id="contratista_id" class="form-control select2-agro w-100">
                                        <option value="">Seleccione o busque contratista...</option>
                                        @foreach($contratistas as $c)
                                            <option value="{{ $c->id }}">{{ $c->nombre }} ({{ $c->rif ?? 'S/R' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-section flex-grow-1">
                    <div class="section-title">
                        <span>3. Selección en Mapa</span> 
                        <span class="badge badge-success px-2 py-1"><span id="total-ha-count">0.00</span> Ha</span>
                    </div>
                    <div id="lista-tablones" class="d-flex flex-wrap mb-3">
                        <div class="w-100 text-center text-muted small py-3" style="border: 1px dashed #ccc; border-radius: 5px;">
                            <i class="fas fa-hand-pointer mb-2" style="font-size: 1.5rem;"></i><br>Haga clic en los tablones del mapa
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-white mt-auto" style="border-top: 1px solid #eee;">
                    <button type="submit" class="btn btn-success btn-block btn-lg shadow" id="btnSubmit">
                        <i class="fas fa-save mr-1"></i> REGISTRAR JORNADA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="template_maquinaria">
    <div class="maquina-card">
        <i class="fas fa-times btn-remove-row" onclick="removeMaquinariaRow(this)"></i>
        <div class="form-group mb-2">
            <select name="maquinarias[__ID__][id]" class="form-control select2-dinamico activo-selector" required>
                <option value="">Buscar Equipo...</option>
                @foreach($activos as $a) <option value="{{ $a->id }}" data-lectura="{{ $a->lectura_actual }}">{{ $a->codigo }} - {{ $a->nombre }}</option> @endforeach
            </select>
        </div>
        <div class="form-group mb-2">
            <select name="maquinarias[__ID__][operador_id]" class="form-control select2-dinamico" required>
                <option value="">Buscar Operador...</option>
                @foreach($operadores as $o) <option value="{{ $o->id }}">{{ $o->nombre_completo }}</option> @endforeach
            </select>
        </div>
        <div class="row no-gutters">
            <div class="col-6 pr-1">
                <input type="number" step="0.01" name="maquinarias[__ID__][h_ini]" class="form-control form-control-sm input-h-ini" placeholder="Horóm. Inicio" required>
            </div>
            <div class="col-6 pl-1">
                <input type="number" step="0.01" name="maquinarias[__ID__][h_fin]" class="form-control form-control-sm input-h-fin" placeholder="Horóm. Final" required>
            </div>
        </div>
        <div class="error-msg text-danger small mt-1 font-weight-bold" style="display:none;"></div>
    </div>
</template>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Variables Globales del Mapa
    let selectedTablones = new Map(); 
    let map;
    let tablonesLayers = {};
    let metaDiaria = 50.00;
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });

    $(document).ready(function() {
        // 1. Inicializar Selectores Nativos
        $('.select2-agro').select2({ theme: 'bootstrap-5', width: '100%' });

        // 2. Inicializar Fila de Maquinaria por defecto
        addMaquinariaRow();

        // 3. Setup de Leaflet
        map = L.map('map-despacho').setView([9.960669, -70.234770], 14);
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] }).addTo(map);

        const coloresEstado = { 'Preparacion': '#36b9cc', 'Crecimiento': '#1cc88a', 'Maduro': '#f6c23e', 'Cosecha': '#e74a3b', 'Inactivo': '#858796' };

        // Render Sectores
        @foreach($sectores as $sector)
            @if($sector->geometria_json)
                (function() {
                    const sectorLayer = L.geoJSON({!! $sector->geometria_json !!}, {
                        style: { fillColor: 'transparent', weight: 3, color: '#2d6a4f', opacity: 0.5, dashArray: '10, 10', interactive: false }
                    }).addTo(map);
                    sectorLayer.bindTooltip("{{ $sector->nombre }}", { permanent: true, direction: 'center', className: 'sector-label' });
                })();
            @endif
        @endforeach

        // Render Tablones
        @foreach($sectores as $sector)
            @foreach($sector->lotes as $lote)
                @foreach($lote->tablones as $tablon)
                    @if($tablon->geometria_json)
                        (function() {
                            const tId = "{{ $tablon->id }}";
                            const tCodigo = "{{ $tablon->codigo_completo }}";
                            const tHectareas = {{ $tablon->hectareas_documento ?? 0 }};
                            const layer = L.geoJSON({!! $tablon->geometria_json !!}, {
                                style: { fillColor: coloresEstado["{{ $tablon->estado }}"] || '#858796', weight: 1, color: 'white', fillOpacity: 0.6 }
                            }).addTo(map);

                            tablonesLayers[tId] = layer;
                            layer.on('click', () => toggleTablon(tId, tCodigo, tHectareas));
                            layer.bindTooltip(`<b>${tCodigo}</b><br>${tHectareas} Ha`);
                            layer.on('mouseover', function() { this.setStyle({ fillOpacity: 0.9 }); });
                            layer.on('mouseout', function() { this.setStyle({ fillOpacity: 0.6 }); });
                        })();
                    @endif
                @endforeach
            @endforeach
        @endforeach
    });

    /* --- LOGICA DE INTERFAZ Y FORMULARIO --- */

    // Control Tipo de Recurso
    function toggleRecursoView(tipo) {
            $('#input_tipo_recurso').val(tipo);
            
            if(tipo === 'maquinaria') {
                // Oculta manual y deshabilita TODOS sus campos
                $('#block_manual').hide().find('input, select').prop('disabled', true);
                // Muestra maquinaria y habilita sus campos
                $('#block_maquinaria').fadeIn().find('input, select').prop('disabled', false);
            } else {
                // Oculta maquinaria y deshabilita TODOS sus campos
                $('#block_maquinaria').hide().find('input, select').prop('disabled', true);
                // Muestra manual y habilita el select de origen (por defecto)
                $('#block_manual').fadeIn().find('#origen_personal').prop('disabled', false);
                // Verifica el estado del contratista
                toggleContratista();
            }
        }

    // Control Contratista Select
    function toggleContratista() {
        // Solo aplicar si estamos en modo manual
        if ($('#input_tipo_recurso').val() !== 'manual') return;

        const val = $('#origen_personal').val();
        if (val === 'outsourcing') {
            $('#div_contratista').slideDown();
            $('#contratista_id').prop('disabled', false).prop('required', true);
        } else {
            $('#div_contratista').slideUp();
            // Deshabilita, quita el required, limpia el valor y actualiza select2
            $('#contratista_id').prop('disabled', true).prop('required', false).val('').trigger('change');
        }
    }

    // Escuchar cambio en el catálogo de labores
    $('#labor_id').on('change', function() {
        const selected = $(this).find(':selected');
        if(!selected.val()) return;

        const requiereMec = selected.data('maquinaria') == 1;
        
        // Auto-seleccionar el tab apropiado
        if(requiereMec) {
            $('#btn_maquinaria').click();
        } else {
            $('#btn_manual').click();
        }

        Toast.fire({ icon: 'success', title: 'Labor configurada. Seleccione los tablones.' });
    });

    /* --- LÓGICA DE MAQUINARIAS DINÁMICAS --- */
    function addMaquinariaRow() {
        let uniqueId = Date.now();
        let template = $('#template_maquinaria').html().replace(/__ID__/g, uniqueId);
        
        $('#maquinaria_wrapper').append(template);
        // Inicializar Select2 SÓLO en los elementos recién creados
        $('.select2-dinamico').last().prev().select2({ theme: 'bootstrap-5', width: '100%' }); // El primero de la fila
        $('.select2-dinamico').last().select2({ theme: 'bootstrap-5', width: '100%' }); // El segundo de la fila
    }

    function removeMaquinariaRow(btn) {
        $(btn).closest('.maquina-card').fadeOut(300, function(){ $(this).remove(); });
    }

    // Auto-llenar horómetro
    $(document).on('change', '.activo-selector', function() {
        const lectura = $(this).find(':selected').data('lectura');
        const card = $(this).closest('.maquina-card');
        if (lectura !== undefined && lectura !== "") {
            card.find('.input-h-ini').val(lectura).attr('min', lectura);
        }
    });

    // Validar horómetro en tiempo real
    $(document).on('input', '.input-h-ini, .input-h-fin', function() {
        const card = $(this).closest('.maquina-card');
        const ini = parseFloat(card.find('.input-h-ini').val()) || 0;
        const fin = parseFloat(card.find('.input-h-fin').val()) || 0;
        const errorMsg = card.find('.error-msg');
        
        if (fin > 0 && fin < ini) {
            card.css('border-left-color', '#e74a3b');
            errorMsg.text('Horómetro final no puede ser menor al inicial.').slideDown();
        } else {
            card.css('border-left-color', '#52b788');
            errorMsg.slideUp();
        }
    });

    /* --- LÓGICA DE MAPA Y TABLONES --- */
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

    window.removerTablon = function(id) { toggleTablon(id, null, 0); };

    function renderTablonesTags() {
        const container = $('#lista-tablones');
        container.empty();
        let totalHa = 0;

        if (selectedTablones.size === 0) {
            container.append('<div class="w-100 text-center text-muted small py-3" style="border: 1px dashed #ccc; border-radius: 5px;"><i class="fas fa-hand-pointer mb-2" style="font-size: 1.5rem;"></i><br>Haga clic en los tablones del mapa</div>');
            updateProgreso(0);
            return;
        }

        selectedTablones.forEach((datos, id) => {
            totalHa += parseFloat(datos.hectareas);
            container.append(`
                <span class="badge badge-tablon">
                    ${datos.codigo} (${datos.hectareas} Ha)
                    <i class="fas fa-times pointer ml-2 text-danger" onclick="removerTablon('${id}')"></i>
                </span>
            `);
        });
        updateProgreso(totalHa);
    }

    function updateProgreso(total) {
        $('#total-ha-count, #ha-actuales').text(total.toFixed(2));
        metaDiaria = parseFloat($('#input-meta').val()) || 1;
        
        let pct = (total / metaDiaria) * 100;
        const barra = $('#barra-progreso');
        
        if (pct >= 100) { barra.removeClass('bg-info').addClass('bg-success'); } 
        else { barra.removeClass('bg-success').addClass('bg-info'); }
        
        barra.css('width', Math.min(pct, 100) + '%');
        $('#pct-texto').text(Math.round(pct));
    }

    $('#input-meta').on('input', function() { updateProgreso(parseFloat($('#ha-actuales').text())); });

    /* --- ENVÍO DEL FORMULARIO --- */
    $('#formLabor').on('submit', function(e) {
        $('.tablon-hidden-input').remove();
        
        if (selectedTablones.size === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: "Mapa Vacío", text: "Debe seleccionar al menos un tablón en el mapa." });
            return false;
        }

        if ($('#input_tipo_recurso').val() === 'maquinaria' && $('.maquina-card').length === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: "Falta Equipo", text: "Agregue al menos una maquinaria para continuar." });
            return false;
        }
        
        selectedTablones.forEach((datos, id) => {
            $('<input>').attr({ type: 'hidden', name: 'tablon_ids[]', class: 'tablon-hidden-input', value: id }).appendTo('#formLabor');
        });

        $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> PROCESANDO...');
        return true;
    });
</script>
@endpush