<x-layout.app meta-title='ZFIP - Admin' meta-description="Sistema de Información Zona Franca Internacional Pereira">
    <x-slot name="contentHeader">
        <div class="content-header py-3">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Subir Hoja de Cálculo</h4>
                            </div>
                            <div class="card-body">
                                <!-- Formulario para subir archivos -->
                                <form id="uploadForm" method="POST" action="{{ route('admin.cargue.upload.file') }}" enctype="multipart/form-data">
                                    @csrf <!-- Token CSRF -->

                                    <div class="form-group">
                                        <label for="fileUpload">Seleccione un archivo:</label>
                                        <div class="custom-file">
                                            <input type="file" id="fileUpload" name="spreadsheet" class="custom-file-input" required />
                                            <label class="custom-file-label" for="fileUpload">Elegir archivo</label>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
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

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-success btn-lg">Subir</button>
                                    </div>
                                </form>

                                <!-- Barra de progreso -->
                                <div class="progress mt-4" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped bg-info" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-center mt-2">
                                    <span id="progressPercentage">0%</span> - <span id="fileSize">0 KB</span>
                                </div>

                                <div id="uploadStatus" class="mt-4"></div>
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

        <!-- JavaScript para mostrar el nombre del archivo seleccionado -->
        <script>
            document.getElementById('fileUpload').addEventListener('change', function(event) {
                var inputFile = event.target;
                var fileName = inputFile.files[0].name;
                var label = inputFile.nextElementSibling;
                label.textContent = fileName;

                // Mostrar el tamaño del archivo seleccionado
                var fileSize = inputFile.files[0].size;
                var fileSizeElement = document.getElementById('fileSize');
                fileSizeElement.textContent = (fileSize / 1024).toFixed(2) + ' KB'; // Convertir a KB
            });
        </script>

        <!-- JavaScript para manejar la subida de archivos con progreso -->
        <script>
            let form = document.querySelector('#uploadForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir la acción predeterminada del formulario

                let formData = new FormData(form);
                let progressBar = document.querySelector('#progressBar');
                let progressPercentage = document.querySelector('#progressPercentage');
                let fileSizeElement = document.querySelector('#fileSize');
                let actionUrl = form.getAttribute('action');

                let fileUpload = document.getElementById('fileUpload').files[0];
                let fileSize = fileUpload.size;

                let ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", function(e) {
                    let percentage = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentage + '%';
                    progressPercentage.textContent = percentage + '%';

                    // Mostrar el tamaño del archivo cargado
                    let loadedSize = e.loaded;
                    fileSizeElement.textContent = (loadedSize / 1024).toFixed(2) + ' KB / ' + (fileSize / 1024).toFixed(2) + ' KB';
                });

                ajax.addEventListener("load", function() {
                    let response = JSON.parse(ajax.responseText);
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message
                        });
                        progressBar.style.width = '0%'; // Resetear barra de progreso
                        progressPercentage.textContent = '0%';
                        fileSizeElement.textContent = '0 KB / ' + (fileSize / 1024).toFixed(2) + ' KB';
                        form.reset(); // Resetear el formulario
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                });

                ajax.open("POST", actionUrl);
                ajax.send(formData);
            });
        </script>
    </x-slot>
</x-layout.app>
