@extends('layouts.app')

@section('content')
 {{-- Mostrar mensajes de sesión --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
<div class="container-fluid">
    <div class="card border-left-danger shadow h-100 py-2 mb-4">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Nueva Investigación de Incidente</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $paciente->nombre_completo }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('medicina.accidentes.store') }}" method="POST">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
        
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">1. Datos del Suceso</h6></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Fecha y Hora del Evento</label>
                            <input type="date" name="fecha_hora_accidente" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lugar del Evento (Granja/Sector)</label>
                            <input type="text" name="lugar_exacto" class="form-control" placeholder="Ej: Taller Central - Fosa 2">
                        </div>
                        <div class="form-group">
                            <label>Tipo de Evento</label>
                            <select name="tipo_evento" class="form-control">
                                <option>Accidente con Tiempo Perdido</option>
                                <option>Accidente sin Tiempo Perdido</option>
                                <option>Incidente (Casi-Accidente)</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">2. Análisis de Causas (Método de Árbol)</h6></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Causas Inmediatas (Acto Inseguro)</label>
                            <textarea name="causas_inmediatas" class="form-control" rows="2" placeholder="Ej: No usar guantes de protección..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Causas Raíz (Falla de Gestión)</label>
                            <textarea name="causas_raiz" class="form-control" rows="2" placeholder="Ej: Falta de programa de capacitación en X área..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow mb-4">
             <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">3. Testigos y Relatos</h6></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Hubo testigos?</label>
                        <textarea name="testigos" class="form-control" rows="2" placeholder="Ingrese los testigos, separados por comas, (Si Aplica)"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Relatos de lo acontecido</label>
                        <textarea name="descripcion_relato" class="form-control" rows="2" placeholder="Ingrese un pequeño relato de lo sucedido"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Descripcion de Lesiones</label>
                        <textarea name="lesion_detallada" class="form-control" rows="2" placeholder="Ingrese detalles de las Lesiones (Si Aplica)"></textarea>
                    </div>
                </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-gray-100">
                <h6 class="m-0 font-weight-bold text-primary">3. Plan de Acción Correctiva</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <textarea name="acciones_correctivas" class="form-control" rows="3" placeholder="Describa qué se hará para evitar la recurrencia..."></textarea>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-danger btn-icon-split shadow-sm">
                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                        <span class="text">Cerrar Investigación</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection