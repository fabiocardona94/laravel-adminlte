<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <meta name="description" content="{{ $metaDescription ?? "" }}" />
    <meta name="keywords" content="{{ $metaKeywords ?? "" }}" />
    <meta name="author" content="{{ $metaAuthor ?? "" }}" />
    <meta name="copyright" content="{{ $metaCopyRight ?? "" }}" />
    <meta http-equiv="cache-control" content="no-cache" />

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css">
    <!-- Incluye los estilos de Dropzone.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">



    <title>{{ config("app.name", "Laravel") }} - {{ $metaTitle ?? "" }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css" />

    @stack("styles")


    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">
        <!-- Navbar -->
        <x-layout.navbar />
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <x-layout.menu />


        <!-- Content Wrapper. Contains page content -->
        <main>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                @isset($contentHeader)
                    {{ $contentHeader ?? "" }}
                @endisset
                {{--
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0">Starter Page</h1>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Starter Page</li>
                                    </ol>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                    </div>
                    <!-- /.content-header -->
                --}}

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        {{-- Anexas los rows y cols  --}}
                        {{ $slot }}
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
        </main>
        <!-- /.content-wrapper -->


        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="d-none d-sm-inline float-right"></div>
            <!-- Default to the left -->
            <strong>Copyright &copy; {{ date("Y") }}
                <a href="https://adminlte.io/">AdminLTE</a></strong>
        </footer>
    </div>
    <!-- ./wrapper -->



    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script>
    <!-- Incluye los scripts de Dropzone.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    

    @stack('scripts')

    {{-- codigo que permite subir archivos de excel al DB --}}


    <script>
        // Configura Dropzone.js
        Dropzone.autoDiscover = false;
    
        // Inicializa Dropzone en el elemento con id="previews"
        const myDropzone = new Dropzone("#previews", {
            url: "{{ route('upload') }}", // Ruta de la carga en Laravel
            autoProcessQueue: false,
            addRemoveLinks: true,
            uploadMultiple: true,
            parallelUploads: 100,
            maxFiles: 100,
            previewsContainer: "#previews", // Define el contenedor para las vistas previas de archivos
            clickable: ".fileinput-button" // Define el botón que abrirá el selector de archivos
        });
    
        // Maneja el inicio de la carga cuando se hace clic en el botón de iniciar carga
        document.querySelector(".start").addEventListener("click", function() {
            myDropzone.processQueue();
        });
    
        // Maneja la cancelación de la carga cuando se hace clic en el botón de cancelar
        document.querySelector(".cancel").addEventListener("click", function() {
            myDropzone.removeAllFiles(true);
        });
    
        // Actualiza la barra de progreso general
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });
    
        // Muestra la barra de progreso al iniciar la carga
        myDropzone.on("sending", function(file) {
            document.querySelector("#total-progress").style.opacity = "1";
        });
    
        // Oculta la barra de progreso al completar la carga
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0";
        });
    
        // Maneja la respuesta del servidor después de cargar un archivo
        myDropzone.on("success", function(file, response) {
            console.log(response);
            alert('Archivo subido correctamente');
        });
    
        // Maneja los errores del servidor
        myDropzone.on("error", function(file, response) {
            console.error(response);
            alert('Error al subir el archivo');
        });
    </script>
    
</body>

</html>
