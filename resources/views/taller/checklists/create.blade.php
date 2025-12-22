{{-- resources/views/taller/checklists/create.blade.php --}}
@extends('layouts.app') 

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list"></i> Nueva Plantilla de Mantenimiento Preventivo</h6>
        </div>
        <div class="card-body">
            @can('programar_mp')
            <form action="{{ route('checklists.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nombre">Nombre de la Plantilla</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: MP 250H - Tractor" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tipo_activo">Aplica a Activo</label>
                        <select name="tipo_activo" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach ($tipos_activo as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="intervalo_referencia">Intervalo de Referencia</label>
                        <input type="text" name="intervalo_referencia" class="form-control" placeholder="Ej: 250 HRS o 10000 KM" required>
                    </div>
                </div>
                
                <h5 class="mt-4">Tareas a Realizar <small class="text-muted">(Mínimo 1 tarea)</small></h5>
                <div id="tareas-container">
                    <div class="form-group input-group">
                        <input type="text" name="tareas[]" class="form-control" placeholder="Ej: Cambio de Aceite de Motor y Filtro" required>
                        <div class="input-group-append"><button type="button" class="btn btn-danger remove-task"><i class="fas fa-trash"></i></button></div>
                    </div>
                </div>

                <button type="button" id="add-task" class="btn btn-sm btn-secondary mb-3"><i class="fas fa-plus"></i> Agregar Tarea</button>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Guardar Plantilla</button>
                </div>
            </form>
            @else
                <div class="alert alert-danger">No está autorizado para gestionar plantillas de MP.</div>
            @endcan
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('tareas-container');
        const addTaskBtn = document.getElementById('add-task');

        // Función para agregar una nueva tarea
        addTaskBtn.addEventListener('click', function () {
            const newTask = `
                <div class="form-group input-group">
                    <input type="text" name="tareas[]" class="form-control" placeholder="Nueva Tarea..." required>
                    <div class="input-group-append"><button type="button" class="btn btn-danger remove-task"><i class="fas fa-trash"></i></button></div>
                </div>`;
            container.insertAdjacentHTML('beforeend', newTask);
        });

        // Función para eliminar una tarea (manejo de evento delegado)
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-task') || e.target.closest('.remove-task')) {
                const button = e.target.closest('.remove-task');
                if (container.children.length > 1) {
                    button.closest('.input-group').remove();
                } else {
                    alert('Debe haber al menos una tarea en el Checklist.');
                }
            }
        });
    });
</script>
@endsection