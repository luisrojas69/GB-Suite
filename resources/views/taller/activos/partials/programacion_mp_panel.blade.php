@can('programar_mp')
    <h6><i class="fas fa-calendar-check"></i> Programaciones de Mantenimiento Activas</h6>
    
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>MP Programado</th>
                    <th>Última Ejecución</th>
                    <th>Próximo Valor (Meta)</th>
                    <th>Status</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activo->programaciones as $prog)
                    <tr>
                        <td>{{ $prog->checklist->nombre }}</td>
                        <td>{{ $prog->ultima_ejecucion_fecha->format('d/M/Y') }} @if($prog->ultimo_valor_ejecutado) ({{ number_format($prog->ultimo_valor_ejecutado, 0) }} {{ $activo->unidad_medida }}) @endif</td>
                        <td>
                            @if ($prog->proximo_valor_lectura)
                                **{{ number_format($prog->proximo_valor_lectura, 0) }} {{ $activo->unidad_medida }}**
                            @else
                                **{{ $prog->proxima_fecha_mantenimiento->format('d/M/Y') }}**
                            @endif
                        </td>
                        <td>
                             <span class="badge badge-{{ $prog->status == 'Vencido' ? 'danger' : ($prog->status == 'Proximo a Vencer' ? 'warning' : 'success') }}">
                                {{ $prog->status }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('activos.programacion.destroy', ['activo' => $activo->id, 'programacion' => $prog->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Eliminar Programación" onclick="return confirm('¿Seguro que desea eliminar esta programación?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No hay Mantenimientos Preventivos programados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h6><i class="fas fa-plus-circle"></i> Agregar Nueva Programación</h6>
    <form action="{{ route('activos.programacion.store', $activo->id) }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Plantilla de MP</label>
                <select name="checklist_id" class="form-control" required>
                    <option value="">Seleccione Checklist...</option>
                    @foreach (App\Models\Logistica\Taller\Checklist::where('tipo_activo', $activo->tipo)->get() as $checklist)
                        <option value="{{ $checklist->id }}">{{ $checklist->nombre }} ({{ $checklist->intervalo_referencia }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Tipo de Intervalo</label>
                <select name="tipo_intervalo" class="form-control" required>
                    <option value="LECTURA">LECTURA ({{ $activo->unidad_medida }})</option>
                    <option value="MESES">TIEMPO (Meses)</option>
                    <option value="DIAS">TIEMPO (Días)</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Valor Intervalo</label>
                <input type="number" name="intervalo" class="form-control" min="1" required placeholder="Ej: 250 (horas/km) o 6 (meses)">
            </div>
            <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Programar</button>
            </div>
        </div>
    </form>
@else
    <div class="alert alert-danger">No está autorizado para gestionar la programación de MP.</div>
@endcan