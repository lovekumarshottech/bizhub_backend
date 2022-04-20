<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::to('')}}/admins/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::to('')}}/admins/dist/css/adminlte.min.css">

    <link rel="stylesheet" href="{{ URL::to('')}}/admins/plugins/toastr/toastr.min.css">
    @yield('styles')
    <style>
        .dataTables_filter {
            float: right;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ URL::to('')}}/admins/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout.perform') }}" role="button">
                        <i class="fa fa-power-off"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: green;">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{ URL::to('')}}/admins/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">BIZHUB</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ URL::to('')}}/admins/dist/img/avatar4.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ auth()->user()->first_name }}</a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link" @if (Request::is('admin')) style="background-color: #E4D00A; color: white;" @endif>
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view.users') }}" class="nav-link" @if (Request::is('admin/users')) style="background-color: #E4D00A; color: white;" @endif>
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view.categories') }}" class="nav-link" @if (Request::is('admin/categories')) style="background-color: #E4D00A; color: white;" @endif>
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view.jobs') }}" class="nav-link" @if (Request::is('admin/jobs')) style="background-color: #E4D00A; color: white;" @endif>
                                <i class="nav-icon far fa-building"></i>
                                <p>Jobs</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href=" {{ route('view.disputes') }}" class="nav-link" @if (Request::is('admin/disputes')) style="background-color: #E4D00A; color: white;" @endif>
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>Disputes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href=" {{ route('view.queries') }}" class="nav-link" @if (Request::is('admin/queries')) style="background-color: #E4D00A; color: white;" @endif>
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>Queries</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('logout.perform')}}" class="nav-link">
                                <i class="nav-icon fa fa-power-off"></i>
                                <p>
                                    Log Out
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
        <footer id=footer class="main-footer">
            <strong>Copyright &copy; <script>
                    document.getElementById('footer').appendChild(document.createTextNode(new Date().getFullYear()))
                </script> <a href="#">Bizhub</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="{{ URL::to('')}}/admins/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ URL::to('')}}/admins/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::to('')}}/admins/dist/js/adminlte.js"></script>

    <script src="{{ URL::to('')}}/admins/plugins/toastr/toastr.min.js"></script>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    @yield('scripts')
    <script>
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}")
        @endif
    </script>
</body>

</html>