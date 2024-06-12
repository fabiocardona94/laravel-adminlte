<x-layout.app meta-title='ZFIP - Admin' meta-description="Sistema de Informacíón Zona Franca Internacional Pereira">
    <x-slot name="contentHeader">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">Subir Hoja de Cálculo</div>
                        <div class="card-body">
                            <form action="{{ route('upload.file') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="spreadsheet">Seleccionar Archivo:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="spreadsheet" name="spreadsheet" required>
                                        <label class="custom-file-label" for="spreadsheet" id="spreadsheetLabel">Elegir archivo</label>
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

        <script>
            // Mostrar el nombre del archivo seleccionado en el campo de entrada de archivos
            document.getElementById('spreadsheet').addEventListener('change', function() {
                var fileName = this.files[0].name;
                document.getElementById('spreadsheetLabel').innerText = fileName;
            });
        </script>
    </x-slot>
</x-layout.app>
