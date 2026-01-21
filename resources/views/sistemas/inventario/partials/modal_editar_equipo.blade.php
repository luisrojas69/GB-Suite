<div class="modal fade" id="modalEditarEquipo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-left-secondary">
            <form id="formEditarEquipo">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_id" id="edit_item_id">
                
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-secondary">
                        <i class="fas fa-edit"></i> Editar Datos Básicos
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small font-weight-bold">Descripción / Nombre</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Marca</label>
                            <input type="text" name="brand" id="edit_brand" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Modelo</label>
                            <input type="text" name="model" id="edit_model" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Número de Serial</label>
                        <input type="text" name="serial" id="edit_serial" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary">Actualizar Datos</button>
                </div>
            </form>
        </div>
    </div>
</div>