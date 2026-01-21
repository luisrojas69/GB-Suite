@extends('layouts.app')
@section('title', 'Crear Sector')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
<style>
    #map { height: 500px; width: 100%; border-radius: 8px; }
    .form-group label { font-weight: bold; color: #4e73df; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Nuevo Sector</h6></div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('produccion.areas.sectores.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Código del Sector *</label>
                            <input type="text" name="codigo_sector" class="form-control @error('codigo_sector') is-invalid @enderror" value="{{ old('codigo_sector') }}">
                        </div>
                        <div class="form-group">
                            <label>Nombre del Sector *</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                        </div>
                        <input type="hidden" name="geometria" id="geometria_input">
                        
                        <button type="submit" class="btn btn-success btn-block shadow mt-4">
                            <i class="fas fa-save"></i> Guardar Sector
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-dark">Dibujar Límite Geográfico</h6>
                    <small class="text-danger">@error('geometria') El mapa es obligatorio @enderror</small>
                </div>
                <div class="card-body p-1"><div id="map"></div></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
<script>
    $(document).ready(function() {
        const map = L.map('map').setView([9.960669, -70.234770], 13);
        L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', { subdomains:['mt0','mt1','mt2','mt3'] }).addTo(map);

        map.pm.addControls({ position: 'topleft', drawCircle: false, drawMarker: false, drawPolyline: false });

        map.on('pm:create', (e) => {
            $('#geometria_input').val(JSON.stringify(e.layer.toGeoJSON().geometry));
        });
    });
</script>
@endpush