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
                                    <button type="submit" class="btn btn-success">Cargar</button>
                                </form>
                                @if(session('success'))
                                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
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
        </script>
    </x-slot>
</x-layout.app>
