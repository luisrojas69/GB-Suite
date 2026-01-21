<div class="modal fade" id="modalAsignar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-left-primary">
            <form id="formAsignar">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Asignar Equipo: <span id="nombreEquipoModal" class="font-weight-bold"></span></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="item_id_input">
                    <input type="hidden" name="target_type" id="target_type_input">

                    <div class="form-group">
                        <label class="font-weight-bold">Responsable (Empleado o Departamento)</label>
                        <select name="target_id" id="selectResponsable" class="form-control" style="width: 100%" required></select>
                        <small class="text-muted">Busque por nombre de empleado o área.</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Ubicación Física</label>
                        <select name="location_id" id="selectUbicacion" class="form-control" required>
                            <option value="">Seleccione ubicación...</option>
                            @foreach($ubicaciones as $u)
                                <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Accesorios / Consumibles</label>
                        <textarea name="accessories" class="form-control" rows="2" placeholder="Ej: Cargador, Mouse, Maletín..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Confirmar Asignación</button>
                </div>
            </form>
        </div>
    </div>
</div>