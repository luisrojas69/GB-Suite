{{-- resources/views/taller/ordenes/show.blade.php --}}
@extends('layouts.app') 

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Orden de Servicio #{{ $orden->codigo_orden }}</h1>
        <span class="badge badge-lg p-2 badge-{{ $orden->status == 'Abierta' ? 'warning' : ($orden->status == 'En Proceso' ? 'info' : ($orden->status == 'Cerrada' ? 'success' : 'danger')) }}">
            STATUS: {{ strtoupper($orden->status) }}
        </span>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-truck-monster"></i> Activo & Tiempos</h6>
                </div>
                <div class="card-body">
                    <p><strong>Activo:</strong> <a href="{{ route('activos.show', $orden->activo->id) }}">{{ $orden->activo->codigo }} - {{ $orden->activo->nombre }}</a></p>
                    <p><strong>Tipo Mantenimiento:</strong> {{ $orden->tipo_servicio }}</p>
                    <p><strong>Solicitante:</strong> {{ $orden->solicitante->name ?? 'N/A' }}</p>
                    <p><strong>Lectura Inicial:</strong> {{ number_format($orden->lectura_inicial, 0) }} {{ $orden->activo->unidad_medida }}</p>
                    <hr>
                    <p><strong>Ingreso al Taller:</strong> {{ $orden->created_at->format('d/M H:i') }}</p>
                    <p><strong>Inicio Trabajo:</strong> {{ $orden->fecha_inicio_taller ? $orden->fecha_inicio_taller->format('d/M H:i') : 'Pendiente' }}</p>
                    <p><strong>Salida (Fin):</strong> {{ $orden->fecha_salida_taller ? $orden->fecha_salida_taller->format('d/M H:i') : 'Pendiente' }}</p>

                    @can('gestionar_ordenes')
                        @if ($orden->status == 'Abierta')
                            <form action="{{ route('ordenes.iniciar', $orden->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-info btn-block"><i class="fas fa-play"></i> Iniciar Trabajo</button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>

            <div class="card shadow mb-4 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Costo Total Estimado</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($orden->costo_total_servicio, 2) }} Bs</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <hr>
                    <small>Repuestos: {{ number_format($orden->costo_repuestos_total, 2) }} Bs</small><br>
                    <small>Outsourcing: {{ number_format($orden->costo_outsourcing, 2) }} Bs</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="falla-tab" data-toggle="tab" href="#falla" role="tab">1. Diagnóstico</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="repuestos-tab" data-toggle="tab" href="#repuestos" role="tab">2. Repuestos y Costos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="cierre-tab" data-toggle="tab" href="#cierre" role="tab">3. Cierre y Resultados</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="falla" role="tabpanel">
                            <h6><i class="fas fa-exclamation-triangle"></i> Reporte Inicial:</h6>
                            <blockquote class="blockquote border-left-danger p-2 ml-0">
                                <p class="mb-0">{{ $orden->descripcion_falla }}</p>
                            </blockquote>

                            @if ($orden->tipo_servicio == 'Preventivo')
                                <h6 class="mt-4"><i class="fas fa-list-check"></i> Checklist MP:</h6>
                                <p class="text-muted">Tareas pendientes de implementación (sección de checklist).</p>
                                    @if (session('success-task'))
                                        <div class="alert alert-success">{{ session('success-task') }}</div>
                                    @endif
                                    @if (session('error-task'))
                                        <div class="alert alert-danger">{{ session('error-task') }}</div>
                                    @endif
        
                            @if ($orden->checklist && $orden->checklist->detallesChecklist->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>Tarea a Ejecutar</th>
                                                <th width="15%">Completada</th>
                                                <th width="35%">Resultado / Notas</th>
                                                <th width="10%">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Iterar sobre la colección de detalles --}}
                                            @foreach ($orden->checklist->detallesChecklist as $detalle)
                                                
                                                {{-- Cada fila es un formulario que apunta a la nueva ruta de actualización --}}
                                                <form action="{{ route('ordenes.checklist.update_detalle', [
                                                        'orden' => $orden->id, 
                                                        'ordenRepuesto' => $detalle->id 
                                                    ]) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                                                
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $detalle->tarea }}</td> 

                                                        {{-- Columna de Estado (Checkbox) --}}
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" 
                                                                       class="custom-control-input" 
                                                                       id="completado_{{ $detalle->id }}" 
                                                                       name="completado" 
                                                                       value="1" 
                                                                       {{ $detalle->completado ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="completado_{{ $detalle->id }}">
                                                                    <span class="badge badge-{{ $detalle->completado ? 'success' : 'warning' }}">
                                                                        {{ $detalle->completado ? 'Sí' : 'No' }}
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </td>

                                                        {{-- Columna de Notas/Resultado (Textarea) --}}
                                                        <td>
                                                            <textarea name="notas_resultado" 
                                                                      class="form-control form-control-sm" 
                                                                      rows="1" 
                                                                      placeholder="Observaciones de la tarea..."
                                                                      @if ($orden->status == 'Cerrada') disabled @endif 
                                                                      >{{ $detalle->notas_resultado }}</textarea>
                                                        </td>
                                                        
                                                        {{-- Columna de Acción (Botón de Guardar) --}}
                                                        <td class="text-center">
                                                            @if ($orden->status != 'Cerrada' && Gate::allows('gestionar_ordenes'))
                                                                <button type="submit" class="btn btn-primary btn-sm" title="Guardar cambios">
                                                                    <i class="fas fa-save"></i>
                                                                </button>
                                                            @else
                                                                <i class="fas fa-lock text-muted"></i>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </form>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- ... (Small tag) ... --}}
                            @else
                                {{-- ... (Alert) ... --}}
                            @endif
                            @endif
                        </div>

                        <div class="tab-pane fade" id="repuestos" role="tabpanel">
                            <div id="repuestos-container">
                                @include('taller.ordenes.partials.repuestos_form')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="cierre" role="tabpanel">
                            @if ($orden->status == 'En Proceso' && Gate::allows('gestionar_ordenes'))
                                <h6>Finalizar Trabajo y Cierre de Orden</h6>
                                <form action="{{ route('ordenes.cerrar', $orden->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="lectura_final">Lectura Final ({{ $orden->activo->unidad_medida }})</label>
                                        <input type="number" name="lectura_final" class="form-control" required min="{{ $orden->lectura_inicial }}" placeholder="Lectura final del Horómetro/Odómetro">
                                    </div>
                                    <div class="form-group">
                                        <label for="tareas_realizadas">Tareas Realizadas y Observaciones Finales</label>
                                        <textarea name="tareas_realizadas" class="form-control" rows="4" required placeholder="Detalle el trabajo ejecutado, resultados de pruebas, etc."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block mt-4" onclick="return confirm('¿Confirma que la orden está completa y el activo listo para operar?')"><i class="fas fa-check"></i> CERRAR ORDEN</button>
                                </form>
                            @elseif ($orden->status == 'Cerrada')
                                <h6>Resultados Finales:</h6>
                                <p><strong>Fecha de Cierre:</strong> {{ $orden->fecha_salida_taller->format('d/M/Y H:i') }}</p>
                                <p><strong>Lectura Final:</strong> {{ number_format($orden->lectura_final, 0) }} {{ $orden->activo->unidad_medida }}</p>
                                <hr>
                                <p><strong>Detalle del Trabajo:</strong></p>
                                <blockquote class="blockquote border-left-success p-2 ml-0">
                                    <p class="mb-0">{{ $orden->tareas_realizadas ?? 'Registro pendiente al cerrar.' }}</p>
                                </blockquote>
                            @else
                                <div class="alert alert-warning">Esta sección estará disponible una vez que el trabajo haya iniciado.</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    // Variable para almacenar el token CSRF de Laravel
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    /**
     * Función genérica para manejar peticiones AJAX y recargar la tabla de repuestos
     * @param {string} url - URL de la petición.
     * @param {string} method - Método HTTP (POST, PATCH, DELETE).
     * @param {object|null} data - Datos a enviar (solo para POST/PATCH).
     */
    function manejarAjaxRepuestos(url, method, data = {}) {
        
        // Mostrar un Spinner o indicador de carga (opcional)
        $('#repuestos-container').html('<div class="text-center p-5"><i class="fas fa-sync-alt fa-spin fa-2x"></i> Cargando...</div>');

        $.ajax({
            url: url,
            method: method,
            data: data,
            dataType: 'html', // Esperamos HTML de vuelta (el partial actualizado)
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                // Indica a Laravel que es una petición AJAX (útil en el controlador)
                'X-Requested-With': 'XMLHttpRequest' 
            },
            success: function(response) {
                // Remplazar el contenido del contenedor con el nuevo HTML
                $('#repuestos-container').html(response); 
                // Recargar la información del resumen (columna izquierda)
                actualizarResumenCostos(); 
            },
            error: function(xhr) {
                console.error("Error AJAX:", xhr.responseText);
                // Mostrar un mensaje de error más útil
                $('#repuestos-container').html('<div class="alert alert-danger">Error al procesar la solicitud. Revise la consola.</div>');
            }
        });
    }

    /**
     * 1. Manejar la Adición de Repuestos (Formulario Store)
     */
    $(document).on('submit', '#form-agregar-repuesto', function(e) {
        e.preventDefault(); // Detener el envío normal del formulario
        
        let form = $(this);
        let url = form.attr('action');
        let method = form.attr('method');
        let data = form.serialize(); // Serializar todos los campos del formulario

        manejarAjaxRepuestos(url, method, data);
    });

    /**
     * 2. Manejar la Eliminación de Repuestos (Formulario Destroy)
     */
    $(document).on('submit', '.form-eliminar-repuesto', function(e) {
            e.preventDefault(); // Detener el envío normal del formulario

            let form = $(this);
            let url = form.attr('action');
            let method = 'DELETE'; // El método real
            
            // Usamos SweetAlert2 para confirmar
            Swal.fire({
                title: '¿Está seguro de eliminar?',
                text: "¡Esta acción no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar repuesto',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, ejecutamos la función AJAX
                    manejarAjaxRepuestos(url, method);
                }
            });
        });
    /**
     * 3. Función para actualizar el resumen de costos (Columna izquierda)
     * NOTA: Este es un placeholder. La lógica real depende de cómo Laravel retorne los nuevos costos.
     * La forma más sencilla sería incluir los costos en el partial devuelto (pasos 4.2 y 4.3).
     */
    function actualizarResumenCostos() {
         // Si el controlador te devuelve los nuevos costos en una respuesta JSON, puedes actualizar aquí.
         // PERO, si el controlador solo devuelve el HTML del partial, la forma más fácil es...
         // Recargar la página si la actualización de costos es crítica, o hacer una
         // pequeña petición AJAX adicional para solo obtener los totales.
         // Por ahora, recargaremos la página solo si el total de costos es crítico.
         // Para evitar la recarga, necesitaremos un endpoint para obtener SOLO los costos.
         
         // Para simplificar, y dado que ya estamos haciendo una petición, vamos a hacer 
         // que el partial devuelto tenga los costos actualizados en un campo oculto
         // y los leemos aquí.

         // Ejemplo simple: 
         let nuevoTotal = $('#repuestos-container').find('input[name="costo_repuestos_total_oculto"]').val();
         if (nuevoTotal) {
             // Actualizar el costo en el card de la izquierda (si le das un ID)
             // Ejemplo: $('#costo-repuestos-display').text(nuevoTotal + ' Bs');
             // Y el costo total del servicio.
         }
    }


    // Función que se llamará al hacer clic en el botón de eliminar
    window.confirmDelete = function(formId) {
        Swal.fire({
            title: '¿Está seguro de eliminar?',
            text: "¡Esta acción no se puede revertir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, enviamos el formulario.
                // Usamos el ID del formulario que se pasa como parámetro.
                $('#' + formId).submit();
            }
        });
        return false; // Previene la acción por defecto del botón
    }
});
</script>
@endsection