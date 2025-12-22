@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Monitoreo de Comedor</h1>
        <div>
            <button class="btn btn-secondary btn-sm shadow-sm" onclick="location.reload()">
                <i class="fas fa-sync fa-sm"></i> Actualizar
            </button>
            @can('crear_registros_manuales')
            <button class="btn btn-primary btn-sm shadow-sm" onclick="openManualRecordModal()">
                <i class="fas fa-hand-pointer fa-sm"></i> Registro Manual
            </button>
            @endcan
        </div>
    </div>

    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <form method="GET" action="{{ route('comedor.records.index') }}" class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">Fecha</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">Tipo de Comida</label>
                    <select name="meal_type" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        @foreach($mealTypes as $mt)
                            <option value="{{ $mt->id }}">{{ $mt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Consumo</h6>
            <span class="badge badge-info">Registros de hoy: {{ $records->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="recordsTable" width="100%" cellspacing="0">
                    <thead class="bg-light text-center">
                        <tr>
                            <th>ID Marcaje</th>
                            <th>Empleado / Invitado</th>
                            <th>Tipo Servicio</th>
                            <th>Fecha / Hora</th>
                            <th>Costo</th>
                            <th>Origen</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($records as $record)
                            <tr>
                                <td><strong>{{ $record->employee_id }}</strong></td>
                                <td class="text-left">
                                    @if($record->employee)
                                        <div class="font-weight-bold text-dark">{{ $record->employee->name }}</div>
                                        <small class="text-muted"><i class="fas fa-building"></i> {{ $record->employee->department }}</small>
                                    @else
                                        <div class="text-danger italic">Usuario no sincronizado (ID: {{ $record->employee_id }})</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $record->mealType->name }}</span>
                                </td>
                                <td>{{ $record->punch_time->format('d/m/Y h:i A') }}</td>
                                <td class="font-weight-bold">${{ number_format($record->cost, 2) }}</td>
                                <td>
                                    @if($record->source == 'biometric')
                                        <span class="text-success"><i class="fas fa-fingerprint"></i> ZK</span>
                                    @else
                                        <span class="text-info"><i class="fas fa-keyboard"></i> Manual</span>
                                    @endif
                                </td>
                                <td><i class="fas fa-check-circle text-success"></i></td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7">No hay marcaciones registradas para esta fecha.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $records->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</div>

@can('crear_registros_manuales')
<div class="modal fade" id="manualRecordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Cargar Marcación Manual</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="manualRecordForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle"></i> Use esto solo si el empleado no pudo marcar en el biométrico.
                    </div>
                    <div class="form-group">
                        <label>ID del Empleado / Invitado</label>
                        <input type="number" name="employee_id" class="form-control" placeholder="Ej: 101" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Comida</label>
                        <select name="meal_type_id" class="form-control" required>
                            @foreach($mealTypes as $mt)
                                <option value="{{ $mt->id }}">{{ $mt->name }} (${{ $mt->price }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha y Hora del Consumo</label>
                        <input type="datetime-local" name="punch_time" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea name="observation" class="form-control" rows="2" placeholder="Motivo del registro manual..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script>
    function openManualRecordModal() {
        $('#manualRecordForm')[0].reset();
        $('#manualRecordModal').modal('show');
    }

    $('#manualRecordForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('comedor.records.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#manualRecordModal').modal('hide');
                Swal.fire('¡Éxito!', response.success, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON.error || 'Error al procesar el registro.';
                Swal.fire('Atención', errorMsg, 'warning');
            }
        });
    });
</script>
@endpush