@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">✏️ Editar Gasto #{{ $gasto->id }}</h1>
        <a href="{{ route('produccion.animales.costos.expenses.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> Ver Historial
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Modificar Detalles del Gasto</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('produccion.animales.costos.expenses.update', $gasto->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Método HTTP necesario para UPDATE --}}
                
                <div class="form-row">
                    {{-- 1. Fecha del Gasto --}}
                    <div class="form-group col-md-4">
                        <label for="expense_date">Fecha del Gasto <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', $gasto->expense_date->format('Y-m-d')) }}" required>
                        @error('expense_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 2. Tipo de Gasto (Mapeo Contable) --}}
                    <div class="form-group col-md-4">
                        <label for="cost_type_id">Tipo de Gasto <span class="text-danger">*</span></label>
                        <select class="form-control @error('cost_type_id') is-invalid @enderror" id="cost_type_id" name="cost_type_id" required>
                            <option value="">Seleccione el tipo</option>
                            @foreach ($costTypes as $id => $name)
                                <option value="{{ $id }}" {{ old('cost_type_id', $gasto->cost_type_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('cost_type_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- 3. Monto --}}
                    <div class="form-group col-md-4">
                        <label for="amount">Monto ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $gasto->amount) }}" required placeholder="Ej: 1500.50">
                        @error('amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <hr>

                {{-- SECCIÓN DE REFERENCIA (Animal o Lote) --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Asociar Gasto a: <span class="text-danger">*</span></label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="ref_type_animal" name="reference_type" class="custom-control-input" value="animal" {{ old('reference_type', $gasto->reference_type) == 'animal' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="ref_type_animal">Animal Específico</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="ref_type_location" name="reference_type" class="custom-control-input" value="location" {{ old('reference_type', $gasto->reference_type) == 'location' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="ref_type_location">Lote/Ubicación</label>
                            </div>
                            @error('reference_type') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="reference_id">ID de Referencia (Animal o Lote) <span class="text-danger">*</span></label>
                        <select class="form-control @error('reference_id') is-invalid @enderror" id="reference_id" name="reference_id" required>
                            <option value="">Seleccione la referencia...</option>
                            
                            {{-- OPCIONES DE ANIMALES --}}
                            <optgroup label="Animales (ID)" data-group-type="animal">
                                @foreach ($animals as $id => $code)
                                    <option value="{{ $id }}" data-type="animal" {{ old('reference_id', $gasto->reference_id) == $id && old('reference_type', $gasto->reference_type) == 'animal' ? 'selected' : '' }}>Animal Cód: {{ $code }} (ID: {{ $id }})</option>
                                @endforeach
                            </optgroup>
                            
                            {{-- OPCIONES DE LOTES --}}
                            <optgroup label="Lotes/Ubicaciones (ID)" data-group-type="location">
                                @foreach ($locations as $id => $name)
                                    <option value="{{ $id }}" data-type="location" {{ old('reference_id', $gasto->reference_id) == $id && old('reference_type', $gasto->reference_type) == 'location' ? 'selected' : '' }}>Lote: {{ $name }} (ID: {{ $id }})</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('reference_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                
                {{-- DETALLES ADICIONALES --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="supplier_name">Proveedor</label>
                        <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" id="supplier_name" name="supplier_name" value="{{ old('supplier_name', $gasto->supplier_name) }}" placeholder="Nombre o Razón Social">
                        @error('supplier_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="document_number">Número de Documento / Factura</label>
                        <input type="text" class="form-control @error('document_number') is-invalid @enderror" id="document_number" name="document_number" value="{{ old('document_number', $gasto->document_number) }}" placeholder="Ej: 0001-000000123">
                        @error('document_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Descripción Detallada (Opcional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $gasto->description) }}</textarea>
                    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-info btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-sync"></i></span>
                        <span class="text">Actualizar Gasto</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const refTypeRadios = document.querySelectorAll('input[name="reference_type"]');
        const referenceSelect = document.getElementById('reference_id');
        const animalOptGroup = referenceSelect.querySelector('optgroup[data-group-type="animal"]');
        const locationOptGroup = referenceSelect.querySelector('optgroup[data-group-type="location"]');

        function toggleReferenceFields() {
            const selectedType = document.querySelector('input[name="reference_type"]:checked').value;
            
            // Mostrar u ocultar los grupos completos de opciones
            animalOptGroup.style.display = (selectedType === 'animal' ? 'block' : 'none');
            locationOptGroup.style.display = (selectedType === 'location' ? 'block' : 'none');
            
            // Opcional: Asegurar que el valor seleccionado aún sea visible
            let currentSelectedOption = referenceSelect.querySelector(`option[value="${referenceSelect.value}"]`);
            
            if (currentSelectedOption && currentSelectedOption.dataset.type !== selectedType) {
                // Si la referencia seleccionada pertenece al tipo oculto, se limpia la selección para evitar errores.
                 referenceSelect.value = "";
            }
        }

        refTypeRadios.forEach(radio => radio.addEventListener('change', toggleReferenceFields));
        
        // Ejecutar al cargar para inicializar el estado
        toggleReferenceFields();
    });
</script>

@endsection