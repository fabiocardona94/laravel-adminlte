<x-layout.app meta-title='ZFIP - Admin' meta-description="Sistema de Información Zona Franca Internacional Pereira">
    <x-slot name="contentHeader">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Subir Hoja de Cálculo</div>
                            <div class="card-body">
                                <form action="{{ route('admin.cargue.upload.file') }}" method="post" enctype="multipart/form-data" id="uploadForm">
                                    @csrf
                                    <div class="form-group">
                                        <label for="spreadsheet">Seleccionar Archivo:</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="spreadsheet" name="spreadsheet" accept=".xls,.xlsx" required>
                                            <label class="custom-file-label" for="spreadsheet" id="spreadsheetLabel">Elegir archivo</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>¿Qué desea hacer con los datos?</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="replaceData" name="action" class="custom-control-input" value="replace" checked>
                                            <label class="custom-control-label" for="replaceData">Reemplazar Datos</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="appendData" name="action" class="custom-control-input" value="append">
                                            <label class="custom-control-label" for="appendData">Anexar Datos</label>
                                        </div>
                                    </div>
                                    <button class="btn btn-success" type="submit">Cargar</button>
                                </form>
                                @if(session('success'))
                                    <div class="alert alert-success mt-3" id="successMessage">{{ session('success') }}</div>
                                @endif
                                @if($errors->any())
                                    <div class="alert alert-danger mt-3">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 CSS y JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

        <!-- jQuery y jQuery Form -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

        <script>
            // Mostrar el nombre del archivo seleccionado en el campo de entrada de archivos
            document.getElementById('spreadsheet').addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    let fileName = this.files[0].name;
                    document.getElementById('spreadsheetLabel').innerText = fileName;
                } else {
                    document.getElementById('spreadsheetLabel').innerText = 'Elegir archivo';
                }
            });

            $(document).ready(function() {
                $('#uploadForm').ajaxForm({
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Subiendo...',
                            text: 'El archivo está siendo subido.',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        let percent = Math.round((position / total) * 100);
                        Swal.update({
                            title: 'Subiendo...',
                            html: `El archivo se está subiendo: ${percent}% completado.`,
                            timerProgressBar: true,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    complete: function(xhr) {
                        Swal.hideLoading();
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.status === 'success') {
                                Swal.fire({
                                    title: '¡Éxito!',
                                    text: xhr.responseJSON.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    $('#uploadForm')[0].reset();
                                    document.getElementById('spreadsheetLabel').innerText = 'Elegir archivo';
                                });
                            } else if (xhr.responseJSON.status === 'error') {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un problema al subir el archivo.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    }
                });
            });
        </script>
    </x-slot>
</x-layout.app>
