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
                <a href="https://www.siocoytex.com">SIO-CO&TEX</a></strong>
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

    @stack('scripts')
</body>

</html>
