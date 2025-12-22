{{-- resources/views/taller/reportes/gerencial.blade.php --}}
@extends('layouts.app') 

@section('content')
    <h1 class="h3 mb-4 text-gray-800">📊 Reporte Gerencial Consolidado de Flota</h1>

    @if (isset($kpis['message']))
        <div class="alert alert-info">{{ $kpis['message'] }}</div>
    @else
        <div class="row">
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Costo Total de Mantenimiento</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($kpis['costo_total_flota'], 2) }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Costo por Unidad de Uso (H/Km)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($kpis['costo_por_uso_unidad'], 2) }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-tachometer-alt fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Disponibilidad Operativa</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kpis['disponibilidad_flota'] }}%</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">MTTR (Tiempo Medio de Reparación)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kpis['mttr_horas'] ?? 'N/A' }} Horas</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Proporción de Costos (Preventivo vs Correctivo)</h6>
                    </div>
                    <div class="card-body">
                        <h4 class="small font-weight-bold">Mantenimiento Preventivo (MP) <span class="float-right">{{ $kpis['porcentaje_mp_vs_mc'] }}%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $kpis['porcentaje_mp_vs_mc'] }}%" aria-valuenow="{{ $kpis['porcentaje_mp_vs_mc'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Mantenimiento Correctivo (MC) <span class="float-right">{{ $kpis['porcentaje_mc_vs_mp'] }}%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $kpis['porcentaje_mc_vs_mp'] }}%" aria-valuenow="{{ $kpis['porcentaje_mc_vs_mp'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mt-3">Total Costo MP: ${{ number_format($kpis['costo_mp'], 2) }} | Total Costo MC: ${{ number_format($kpis['costo_mc'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection