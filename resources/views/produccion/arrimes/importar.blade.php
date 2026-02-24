@extends('layouts.app')
@section('title-page', 'Importar Arrime - Central Pastora')

@section('styles')
<style>
    :root {
        --agro-dark: #1b4332;
        --agro-primary: #2d6a4f;
        --agro-accent: #52b788;
    }
    .page-header-agro {
        background: linear-gradient(135deg, var(--agro-dark) 0%, var(--agro-primary) 100%);
        color: white; padding: 25px 30px; border-radius: 15px; margin-bottom: 25px;
    }
    .upload-zone {
        border: 2px dashed #d1d3e2;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        transition: all 0.3s;
        background: #f8f9fc;
    }
    .upload-zone:hover {
        border-color: var(--agro-accent);
        background: #fff;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header-agro shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 font-weight-bold mb-1">
                    <i class="fas fa-file-import mr-2"></i> Módulo de Importación
                </h1>
                <p class="mb-0 opacity-75">Carga de Boletos de Arrime desde Oracle (Central Pastora)</p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('produccion.arrimes.index') }}" class="btn btn-light btn-sm rounded-pill px-3">
                    <i class="fas fa-list mr-1"></i> Ver Historial
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Subir Archivo de Datos (CSV/TXT)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('produccion.arrimes.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-zone mb-4">
                            <i class="fas fa-cloud-upload-alt fa-3x text-gray-300 mb-3"></i>
                            <h5>Arrastra el archivo aquí</h5>
                            <p class="text-muted small">Formatos permitidos: .csv, .txt (Delimitado por comas)</p>
                            <input type="file" name="archivo_csv" class="form-control-file" id="archivo_csv" required style="display: none;">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('archivo_csv').click()">
                                Seleccionar Archivo
                            </button>
                            <div id="file-name" class="mt-2 font-weight-bold text-success"></div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Al subir el archivo, el sistema entrará en modo <strong>"Validación"</strong> para verificar tablones y equipos antes de guardar.
                        </div>

                        <button type="submit" class="btn btn-primary btn-block text-white shadow">
                            <i class="fas fa-search mr-1"></i> Iniciar Validación del Reporte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Estructura Requerida del Archivo</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">El archivo debe contener exactamente estos encabezados en la primera fila:</p>
                    <div class="bg-light p-3 rounded mb-3">
                        <code>Boleto, Remesa, Cod_Hacienda, Tablon, Fecha_Quema, Fecha_Arrime, Toneladas_Netas, Rendimiento, Trash_Porc, Cod_Jaiba, ID_Chofer, Dia_Zafra</code>
                    </div>
                    <ul class="small">
                        <li><strong>Cod_Hacienda:</strong> Formato Central (Ej: 00008-06).</li>
                        <li><strong>Tablon:</strong> Código interno del central (Ej: 015).</li>
                        <li><strong>Fechas:</strong> Formato sugerido AAAA-MM-DD HH:MM:SS.</li>
                        <li><strong>Decimales:</strong> Usar punto (.) como separador.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar nombre del archivo seleccionado
    document.getElementById('archivo_csv').onchange = function () {
        document.getElementById('file-name').innerHTML = '<i class="fas fa-file-csv"></i> ' + this.files[0].name;
    };
</script>
@endpush