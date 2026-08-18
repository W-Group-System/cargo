@php
    use \App\Classes\RolesAccessClass;
    $modules = RolesAccessClass::GetUserAccessPerRole(auth()->user()->role);
    // dd($modules);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cargo Visibility and Order Management</title>
    <link rel="shortcut icon" href="{{ asset('images/logo-only.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/iconly/bold.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{-- <link rel="stylesheet" href="{{ asset('js/DataTables/dataTables.bootstrap5.min.css') }}">
     --}}
     <link rel="stylesheet"
      href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
     <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Optional: Select2 Bootstrap5 Theme (looks better) -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('images/loader.gif')}}") 50% 50% no-repeat white ;
            opacity: .8;
            background-size: 120px 120px;
        }
        thead tr:first-child th {
            background-color: #2D589F !important;
            color: #fff !important;
        }

        thead tr:first-child th:first-child {
            border-top-left-radius: 10px !important;
        }

        thead tr:first-child th:last-child {
            border-top-right-radius: 10px !important;
        }

        .clickable-card {
            cursor: pointer;
            transition: all .2s ease;
        }

        .clickable-card.active {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.35);
        }

        .dataTable th,
        .dataTable td {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div id="loader" style="display:none;" class="loader"></div>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" width="180" alt="Logo" srcset=""></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        @if (count($modules) > 0)
                            @foreach ($modules as $key => $value)
                                <li class="sidebar-title">{{$key}}</li>
                                @foreach ($value as $k => $v)
                                    @if ($v['canRead'] == "1")
                                        <li class="sidebar-item {{ $k == $ActiveModule ? 'active':'' }}">
                                            <a href="{{ url($v['url']) }}" onclick='show()' class='sidebar-link'>
                                                {!! $v['icon'] !!}
                                                <span>{{$k}}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                        <hr>
                        <li class="sidebar-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class='sidebar-link'>
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign out</span>
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('vendors/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    {{-- <script src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/DataTables/dataTables.bootstrap5.min.js') }}"></script> --}}

    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('sweetalert::alert')
    {{-- <script>
        @if(session('error'))
            Swal.fire({
                title: 'Unauthorized!',
                text: @json(session('error')),
                icon: 'error'
            });
        @endif
    </script> --}}
    @yield('footer')
</body>
</html>