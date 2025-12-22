<div class="modal fade" id="desincorporarModal{{ $activo->id }}" tabindex="-1" role="dialog" aria-labelledby="desincorporarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="desincorporarModalLabel">Confirmar Desincorporación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea **desincorporar** el activo **{{ $activo->codigo }} - {{ $activo->nombre }}**?</p>
                <p class="text-danger">Esta acción marcará el activo como 'Desincorporado' y no podrá usarse en nuevas órdenes de servicio. **Esta acción es irreversible**.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('activos.destroy', $activo->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Desincorporar Activo</button>
                </form>
            </div>
        </div>
    </div>
</div>