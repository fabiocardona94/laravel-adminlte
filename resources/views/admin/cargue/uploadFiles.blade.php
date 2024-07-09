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

                                <!-- Contenedor para la animación de carga y el mensaje -->
                                <div class="text-center mt-2" id="processingInfo" style="display: none;">
                                    <img src="{{ asset('images/loading.gif') }}" alt="Cargando..." style="width: 60px; height: 60px;">
                                    <p>Procesando la información...</p>
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

        

        <!-- JavaScript para mostrar el nombre del archivo seleccionado y su tamaño -->
        <script>
            document.getElementById('fileUpload').addEventListener('change', function(event) {
                var inputFile = event.target;
                var fileName = inputFile.files[0].name;
                var label = inputFile.nextElementSibling;
                label.textContent = fileName;

                // Mostrar el tamaño del archivo seleccionado
                var fileSize = inputFile.files[0].size;
                var fileSizeElement = document.getElementById('fileSize');
                fileSizeElement.textContent = formatFileSize(fileSize);
            });

            function formatFileSize(size) {
                if (size >= 1073741824) {
                    return (size / 1073741824).toFixed(2) + ' GB';
                } else if (size >= 1048576) {
                    return (size / 1048576).toFixed(2) + ' MB';
                } else {
                    return (size / 1024).toFixed(2) + ' KB';
                }
            }
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
                let processingInfo = document.querySelector('#processingInfo');
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
                    fileSizeElement.textContent = formatFileSize(loadedSize) + ' / ' + formatFileSize(fileSize);

                    // Mostrar mensaje de procesamiento cuando la carga llega al 100%
                    if (percentage === 100) {
                        processingInfo.style.display = 'block';
                    }
                });

                ajax.addEventListener("load", function() {
                    let response = JSON.parse(ajax.responseText);
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message,
                            timer: 6000
                        });

                        // Resetear barra de progreso y otros indicadores
                        progressBar.style.width = '0%';
                        progressPercentage.textContent = '0%';
                        fileSizeElement.textContent = '0 KB';
                        processingInfo.style.display = 'none';

                        // Resetear el formulario y el nombre del archivo
                        form.reset();
                        document.querySelector('.custom-file-label').textContent = 'Elegir archivo';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                        processingInfo.style.display = 'none';
                    }
                });

                ajax.addEventListener("error", function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema con la subida del archivo. Por favor, inténtelo de nuevo.'
                    });
                    processingInfo.style.display = 'none';
                });

                ajax.open("POST", actionUrl);
                ajax.send(formData);
            });

            function formatFileSize(size) {
                if (size >= 1073741824) {
                    return (size / 1073741824).toFixed(2) + ' GB';
                } else if (size >= 1048576) {
                    return (size / 1048576).toFixed(2) + ' MB';
                } else {
                    return (size / 1024).toFixed(2) + ' KB';
                }
            }
        </script>
    </x-slot>
</x-layout.app>
