@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Centro de Mando - iClock 360</h1>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-{{ $info['online'] ? 'success' : 'danger' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Estado del Hardware</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $info['online'] ? 'EN LÍNEA' : 'DESCONECTADO' }}
                            </div>
                            @if($info['online'])
                                <small class="text-muted">Hora: {{ $info['time'] }}</small>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-signal fa-2x {{ $info['online'] ? 'text-success' : 'text-danger' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($info['online'])
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4"><strong>Firmware:</strong> {{ $info['version'] }}</div>
                        <div class="col-md-4"><strong>Plataforma:</strong> {{ $info['platform'] }}</div>
                        <div class="col-md-4"><strong>Usuarios:</strong> {{ $info['users'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Consola de Comandos Remotos</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <button class="btn btn-info btn-block py-3" onclick="sendCommand('enable')">
                        <i class="fas fa-unlock d-block mb-2"></i> Habilitar Equipo
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-warning btn-block py-3 text-dark" onclick="sendCommand('disable')">
                        <i class="fas fa-lock d-block mb-2"></i> Bloquear Pantalla
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-primary btn-block py-3" onclick="sendCommand('sync_time')">
                        <i class="fas fa-clock d-block mb-2"></i> Sincronizar Hora
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-secondary btn-block py-3" onclick="sendCommand('test_voice')">
                        <i class="fas fa-volume-up d-block mb-2"></i> Prueba de Voz
                    </button>
                </div>

                <div class="col-md-4 mb-3">
                    <button class="btn btn-dark btn-block py-3" onclick="confirmCommand('restart', '¿Reiniciar el equipo?')">
                        <i class="fas fa-redo d-block mb-2"></i> Reiniciar Dispositivo
                    </button>
                </div>
                <div class="col-md-4 mb-3">
                    <button class="btn btn-danger btn-block py-3" onclick="confirmCommand('clear_admin', '¡Esto eliminará todos los administradores del equipo!')">
                        <i class="fas fa-user-shield d-block mb-2"></i> Quitar Privilegios Admin
                    </button>
                </div>
                <div class="col-md-4 mb-3">
                    <button class="btn btn-outline-danger btn-block py-3" onclick="confirmCommand('shutdown', '¿Apagar el equipo? Requerirá encendido manual.')">
                        <i class="fas fa-power-off d-block mb-2"></i> Apagar Equipo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-import"></i> Traer Datos (ZK → Servidor)</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Fuerza la descarga de datos sin esperar al proceso automático.</p>
                    <button class="btn btn-outline-primary btn-block" onclick="forceSync('attendance')">
                        Sincronizar Marcaciones Ahora
                    </button>
                    <button class="btn btn-outline-info btn-block" onclick="forceSync('users')">
                        Sincronizar Nombres de Empleados
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-file-export"></i> Enviar Datos (Servidor → ZK)</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Seleccione un empleado local para registrarlo en el equipo físico.</p>
                    <div class="form-group">
                        <select id="employee_to_push" class="form-control select2">
                            <option value="">Seleccione Empleado...</option>
                            @foreach(App\Models\RRHH\Comedor\DiningEmployee::orderBy('name')->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->biometric_id }} - {{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success btn-block" onclick="pushEmployee()">
                        Registrar en Biométrico
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function sendCommand(cmd) {
        Swal.fire({
            title: 'Procesando...',
            text: 'Enviando comando al biométrico',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: "{{ route('comedor.device.execute') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", command: cmd },
            success: function(res) {
                Swal.fire('Éxito', res.success, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.error, 'error');
            }
        });
    }

    function confirmCommand(cmd, msg) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, ejecutar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) { sendCommand(cmd); }
        });
    }



    function forceSync(type) {
    Swal.fire({
            title: 'Sincronizando...',
            didOpen: () => { Swal.showLoading(); }
        });

        $.post("{{ route('comedor.device.forceSync') }}", {
            _token: "{{ csrf_token() }}",
            type: type
        })
        .done(res => Swal.fire('Listo', res.success, 'success'))
        .fail(err => Swal.fire('Error', 'Fallo en la sincronización', 'error'));
    }

    function pushEmployee() {
        let empId = $('#employee_to_push').val();
        if(!empId) return Swal.fire('Atención', 'Seleccione un empleado', 'warning');

        Swal.fire({
            title: 'Enviando...',
            text: 'Registrando usuario en el hardware',
            didOpen: () => { Swal.showLoading(); }
        });

        $.post("{{ route('comedor.device.push') }}", {
            _token: "{{ csrf_token() }}",
            employee_id: empId
        })
        .done(res => Swal.fire('Éxito', res.success, 'success'))
        .fail(err => Swal.fire('Error', 'No se pudo enviar el usuario', 'error'));
    }

</script>
@endpush