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
                    <table id="solicitudes" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tipo Solicitud</th>
                                <th>Observación</th>
                                <th>Fecha de la solicitud</th>
                                <th>Estado</th>
                                <th>Acciones</th>

                            </tr>
                            <tbody>
                                @foreach ($password_list_reset as $list_reset)
                                    <tr>
                                        <td>{{ $list_reset->tipo_solicitud }}</td>
                                        <td>{{ $list_reset->observacion }}</td>
                                        <td>{{ $list_reset->created_at }}</td>
                                        <td>
                                            @if ($list_reset->status==1)
                                                <button type="button" class="btn btn-sm btn-primary">
                                                    Finalizado
                                                </button>
                                                @else
                                                <button type="button" class="btn btn-sm btn-warning">
                                                    En Proceso
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($list_reset->status==1)
                                                <button type="button" class="btn btn-sm btn-outline-primary disabled">
                                                    Email Enviado
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    onclick="ResetPassword({{ $list_reset->id }}, '{{ $list_reset->password_tmp }}', {{ $list_reset->status }}, '{{ $list_reset->tipo_solicitud }}')"
                                                    class="btn btn-sm btn-outline-success">
                                                    Enviar Email
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </thead>
                    </table>
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
                    order: [[ 2, 'desc' ]]
                } );
            } );
        </script>
       <script src="/dist/js/ajax.js"></script>
    @endpush
</x-layout.app>
