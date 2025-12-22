@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Historial de Dotaciones EPP</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-dark text-white">
            <h6 class="m-0 font-weight-bold">Registro General de Entregas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tblDotaciones" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Trabajador</th>
                            <th>Departamento</th>
                            <th>Implementos</th>
                            <th>Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalShowDotacion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalles de Entrega</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="detalleContenido">
                </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let tabla = $('#tblDotaciones').DataTable({
        ajax: "{{ route('medicina.dotaciones.index') }}",
        columns: [
            { data: 'id' },
            { data: 'fecha_entrega' },
            { data: 'paciente.nombre_completo' },
            { data: 'paciente.des_depart' },
            { 
                data: null,
                render: function(data) {
                    let items = [];
                    if(data.calzado_entregado) items.push('<i class="fas fa-boot"></i> Calzado');
                    if(data.pantalon_entregado) items.push('<i class="fas fa-user-tag"></i> Pantalón');
                    if(data.camisa_entregado) items.push('<i class="fas fa-tshirt"></i> Camisa');
                    return items.join(' | ');
                }
            },
            { 
                data: 'entregado_en_almacen',
                render: function(data) {
                  
                    let badge = '';
                    let texto = '';

                    if (data == 1) {
                        badge = 'badge-success';
                        texto = 'Procesado';
                    } else  {
                        badge = 'badge-warning';
                        texto = 'Pendiente';
                    }

                    return `<span class="badge ${badge}">${texto}</span>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="text-center">
                            <button class="btn btn-info btn-circle btn-sm btnShow" data-id="${data.id}"><i class="fas fa-eye"></i></button>
                            <a href="/medicina/dotaciones/ticket/${data.id}" target="_blank" class="btn btn-warning btn-circle btn-sm"><i class="fas fa-qrcode"></i></a>
                             <a href="/medicina/validar-dotacion/${data.qr_token}" target="_blank" class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></a>
                            <button class="btn btn-danger btn-circle btn-sm btnDelete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                        </div>`;
                }
            }
        ],
        language: { url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json" }
    });

    // Acción: Ver (Show)
    $(document).on('click', '.btnShow', function() {
        let id = $(this).data('id');
        $.get(`/medicina/dotaciones/${id}`, function(data) {
            let html = `
                <p><strong>Trabajador:</strong> ${data.paciente.nombre_completo}</p>
                <p><strong>Motivo:</strong> ${data.motivo}</p>
                <p><strong>Observaciones:</strong> ${data.observaciones || 'Sin observaciones'}</p>
                <hr>
                <p><strong>Firma Registrada:</strong></p>
                <img src="${data.firma_digital}" class="img-fluid border">
            `;
            $('#detalleContenido').html(html);
            $('#modalShowDotacion').modal('show');
        });
    });

    // Acción: Eliminar (Delete)
    $(document).on('click', '.btnDelete', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar registro?',
            text: "Esta acción anulará la entrega en el sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/medicina/dotaciones/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        tabla.ajax.reload();
                        Swal.fire('Eliminado', 'Registro borrado.', 'success');
                    }
                });
            }
        });
    });
});
</script>
@endsection