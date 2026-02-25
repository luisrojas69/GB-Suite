@extends('layouts.app')
@section('title-page', 'Importar Rol de Molienda')

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
        cursor: pointer;
    }
    .upload-zone:hover {
        border-color: var(--agro-accent);
        background: #eafff5;
    }
    .upload-icon {
        color: var(--agro-accent);
        transition: transform 0.3s;
    }
    .upload-zone:hover .upload-icon {
        transform: translateY(-5px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header-agro shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 font-weight-bold mb-1">
                    <i class="fas fa-seedling mr-2"></i> Importar Rol de Molienda
                </h1>
                <p class="mb-0 opacity-75">Carga el Excel (CSV) de Planificación de Zafra del Jefe de Cosecha</p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('rol_molienda.index') }}" class="btn btn-light btn-sm rounded-pill px-3 text-success font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Resumen
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4 border-bottom-success">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-file-upload mr-2"></i>Subir Archivo de Datos (CSV)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('rol_molienda.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-zone mb-4" onclick="document.getElementById('archivo_csv').click()">
                            <i class="fas fa-cloud-upload-alt fa-4x upload-icon mb-3"></i>
                            <h5 class="text-dark font-weight-bold">Haz clic o arrastra el archivo aquí</h5>
                            <p class="text-muted small mb-0">Formatos permitidos: .csv (Delimitado por comas)</p>
                            <input type="file" name="archivo_csv" class="form-control-file" id="archivo_csv" required style="display: none;">
                            <div id="file-name" class="mt-3 font-weight-bold text-success"></div>
                        </div>

                        <div class="alert alert-warning small border-left-warning shadow-sm">
                            <i class="fas fa-exclamation-triangle mr-1"></i> 
                            El sistema procesará el archivo en un entorno seguro (Purgatorio) donde podrás corregir Variedades o Tablones no encontrados antes de afectar la base de datos.
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block shadow">
                            <i class="fas fa-magic mr-1"></i> Procesar y Mapear Datos
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-info-circle mr-2"></i>Estructura Requerida del Archivo</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Asegúrate de exportar el Excel del Jefe de Cosecha a CSV con estos encabezados exactos en la primera fila:</p>
                    
                    <div class="bg-dark text-white p-3 rounded mb-3" style="font-family: monospace; font-size: 0.85rem; overflow-x: auto;">
                        Hacienda,Tablon,Variedad,Clase,Has,Tons Has,Rend,Fecha Corte
                    </div>
                    
                    <ul class="small text-dark mt-3">
                        <li class="mb-2"><strong>Hacienda / Sector:</strong> Nombre para ubicar el tablón (Ej: Palo a Pique).</li>
                        <li class="mb-2"><strong>Tablon:</strong> Código interno del tablón (Ej: 011).</li>
                        <li class="mb-2"><strong>Variedad:</strong> Nombre de la semilla (Ej: V-99236). Si no existe, podrás crearla.</li>
                        <li class="mb-2"><strong>Clase:</strong> Plantilla, Soca 1, Soca 2, etc.</li>
                        <li class="mb-2"><strong>Has y Tons Has:</strong> Usar punto (.) para decimales. El sistema calculará el total.</li>
                        <li class="mb-2"><strong>Fecha Corte:</strong> Opcional. Formato sugerido AAAA-MM-DD.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar nombre del archivo seleccionado visualmente
    document.getElementById('archivo_csv').onchange = function () {
        if(this.files.length > 0) {
            document.getElementById('file-name').innerHTML = '<i class="fas fa-file-csv fa-lg mr-2"></i> ' + this.files[0].name;
        }
    };
</script>
@endpush