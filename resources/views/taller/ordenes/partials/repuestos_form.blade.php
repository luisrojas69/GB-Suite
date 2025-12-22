{{-- resources/views/taller/ordenes/partials/repuestos_form.blade.php --}}

<h6>Repuestos e Insumos Utilizados</h6>
<p class="text-muted">Costo Promedio debe ser suministrado por Almacén Central (Profit).</p>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Repuesto</th>
                <th>Cód. Inv.</th>
                <th>Cantidad</th>
                <th>Costo Unit.</th>
                <th>Costo Total</th>
                @if ($orden->status != 'Cerrada' && Gate::allows('gestionar_ordenes'))
                    <th>Acción</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($orden->repuestos as $repuesto)
                <tr>
                    <td>{{ $repuesto->nombre_repuesto }}</td>
                    <td>{{ $repuesto->codigo_inventario ?? 'N/A' }}</td>
                    <td>{{ number_format($repuesto->cantidad_utilizada, 2) }}</td>
                    <td>{{ number_format($repuesto->costo_unitario, 2) }}</td>
                    <td><strong>{{ number_format($repuesto->costo_total, 2) }}</strong></td>
                    @if ($orden->status != 'Cerrada' && Gate::allows('gestionar_ordenes'))
                        <td>
                            <form action="{{ route('ordenes.repuestos.destroy', ['orden' => $orden->id, 'ordenRepuesto' => $repuesto->id]) }}" method="POST" class="d-inline form-eliminar-repuesto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay repuestos cargados.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">**TOTAL REPUESTOS:**</td>
                <td><strong>{{ number_format($orden->costo_repuestos_total, 2) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

@if ($orden->status != 'Cerrada' && Gate::allows('gestionar_ordenes'))
    <hr>
    <h6 class="mt-4">**Agregar Nuevo Repuesto:**</h6>
    <form action="{{ route('ordenes.repuestos.store', $orden->id) }}" method="POST" id="form-agregar-repuesto">
        @csrf
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <input type="text" name="nombre_repuesto" class="form-control form-control-sm" placeholder="Nombre Repuesto (ej: Aceite)" required>
            </div>
            <div class="col-md-2 mb-2">
                <input type="number" name="cantidad_utilizada" step="0.5" class="form-control form-control-sm" placeholder="Cantidad" required min="0">
            </div>
            <div class="col-md-3 mb-2">
                <input type="number" name="costo_unitario" step="0.5" class="form-control form-control-sm" placeholder="Costo Unitario" required min="0">
            </div>
             <div class="col-md-2 mb-2">
                <input type="text" name="codigo_inventario" class="form-control form-control-sm" placeholder="Cód. Inventario (Opcional)">
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-sm btn-info w-100">Agregar</button>
            </div>
        </div>
    </form>
@endif