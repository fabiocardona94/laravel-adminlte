<x-layout.app meta-title='ZFIP - Admin' meta-description="Sistema de Información Zona Franca Internacional Pereira">
    <x-slot name="contentHeader">
    </x-slot>
    <div class="content-header">
        <div class="container-fluid">
            @if (session('status'))
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            title: 'Listo',
                            icon: 'success',
                            text: 'La solicitud ha sido enviada',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: "#00A100",
                        });
                    });
                </script>
            @endif
            <div class="row mb-2">
                <div class="col-sm-12 ">
                    <h1 class="m-0 mb-3">Lista Solicitudes</h1>
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#resetPassword"><i class="fas fa-key mr-1"></i>Restablecer Contraseña</button>
                    </div>
                    <div class="table-responsive">
                        <table id="solicitudes" class="table table-striped table-bordered mt-2 mb-2" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombre usuario</th>
                                    <th>Tipo Solicitud</th>
                                    <th>Observación</th>
                                    <th>Fecha de la solicitud</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Filtrar</th>
                                    <th>Tipo Solicitud</th>
                                    <th>Observación</th>
                                    <th>Fecha de Creación</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="resetPassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="fas fa-tools mt-2"></i>
                    <h5 class="modal-title ml-2" id="exampleModalLabel">Solicitar restablecimiento de contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.solicitar.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <i class="fas fa-id-card"></i>
                            <label for="cedula" class="col-form-label">Cédula</label>
                            <input type="text" value="{{Auth::user()->username}}" class="form-control" id="cedula" name="cedula" required readonly>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-envelope"></i>
                            <label for="email" class="col-form-label">Email</label>
                            <input type="email" value="{{Auth::user()->email}}" class="form-control" id="email" name="email" required readonly>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-question-circle"></i>
                            <label for="tipo_solicitud" class="col-form-label">Motivo por el cual desea restablecer la contraseña</label>
                            <select  class="form-control mt-1" id="tipo_solicitud" name="tipo_solicitud">
                                <option value="OLVIDO">OLVIDO</option>
                                <option value="BLOQUEO">BLOQUEO</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-pencil-alt"></i>
                            <label for="observacion" class="col-form-label">Observación</label>
                            <textarea class="form-control mt-1" id="observacion" name="observacion" placeholder="Ingrese la razon por la cual solicita el restablecimiento de la contraseña" cols="10" rows="2" ></textarea>
                            @error('observacion')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="text-center mb-2 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-success">Realizar Solicitud</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#solicitudes').DataTable( {
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                    },
                    // responsive: true,
                    // dom:'Bfrtilp',
                    // buttons: [
                    //     {
                    //         extend: 'excelHtml5',
                    //         text: '<i class="fas fa-file-excel"></i>',
                    //         titleAttr: 'Exportar en formato Excel',
                    //         className: 'btn btn-success'
                    //     },
                    //     {
                    //         extend: 'pdfHtml5',
                    //         text: '<i class="fas fa-file-pdf"></i>',
                    //         titleAttr: 'Exportar en formato Pdf',
                    //         className: 'btn btn-danger'
                    //     },
                    // ],
                    order: [[ 3, 'desc' ]],
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.solicitar.datatable') }}',
                    columns: [
                        { data: 'user_name', name: 'user_name' },
                        { data: 'tipo_solicitud', name: 'tipo_solicitud' },
                        { data: 'observacion', name: 'observacion' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    initComplete: function () {
                        $('#solicitudes tfoot tr').appendTo('#solicitudes thead');
                        this.api()
                            .columns()
                            .every(function () {
                                let column = this;
                                let title = column.footer().textContent;
                
                                // Create input element
                                let input = document.createElement('input');
                                input.placeholder = title;
                                column.footer().replaceChildren(input);
                
                                // Event listener for user input
                                input.addEventListener('keyup', () => {
                                    if (column.search() !== this.value) {
                                        column.search(input.value).draw();
                                    }
                                });
                            });
                    }
                } );
            } );
        </script>
       <script src="/dist/js/request_password/ajax.js"></script>
    @endpush
</x-layout.app>
