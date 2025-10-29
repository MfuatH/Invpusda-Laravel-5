<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Sistem Inventaris PUSDA'))</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        
        /* Sidebar Styles */
        #sidebar-wrapper {
            min-height: 100vh;
            width: var(--sidebar-width);
            margin-left: -var(--sidebar-width);
            transition: margin 0.25s ease-out;
            background: #4e73df;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            position: fixed;
            z-index: 1000;
        }
        
        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
            color: white;
        }
        
        #sidebar-wrapper .list-group {
            width: var(--sidebar-width);
        }
        
        #sidebar-wrapper .list-group-item {
            border: none;
            padding: 1rem 1.25rem;
            color: rgba(255, 255, 255, .8);
            background: transparent;
            font-size: 0.9rem;
        }
        
        #sidebar-wrapper .list-group-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, .1);
        }
        
        #sidebar-wrapper .list-group-item.active {
            color: #fff;
            background: rgba(255, 255, 255, .15);
        }
        
        #sidebar-wrapper .list-group-item i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Wrapper */
        #page-content-wrapper {
            min-width: 100vw;
            margin-left: 0;
            transition: margin 0.25s ease-out;
        }
        
        /* When sidebar is toggled */
        body.sidebar-toggled #sidebar-wrapper {
            margin-left: 0;
        }
        
        body.sidebar-toggled #page-content-wrapper {
            margin-left: var(--sidebar-width);
            min-width: calc(100vw - var(--sidebar-width));
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: white !important;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important;
            padding: 0.75rem 1rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: #4e73df !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fc;
        }

        /* User Dropdown Styles */
        .img-profile {
            object-fit: cover;
        }
        
        .dropdown-menu {
            font-size: 0.85rem;
            border: none;
        }
        
        .animated--grow-in {
            animation-name: growIn;
            animation-duration: 200ms;
            animation-timing-function: transform cubic-bezier(0.18, 1.25, 0.4, 1), opacity cubic-bezier(0, 1, 0.4, 1);
        }
        
        @keyframes growIn {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .dropdown-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding-top: 1rem;
            margin-top: -0.5rem;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1.5rem;
        }

        .dropdown-item:active {
            background-color: #4e73df;
            color: white;
        }

        .dropdown-item:active i {
            color: white !important;
        }

        /* Responsive Adjustments */
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            
            #page-content-wrapper {
                min-width: 0;
                margin-left: var(--sidebar-width);
            }
            
            .navbar-expand-md .navbar-toggler {
                display: none;
            }
        }
        
        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        /* Button Styles */
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        /* Alert Styles */
        .alert {
            border: none;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
        }
    </style>

    @stack('styles')
    @yield('head')
</head>
<body class="{{ Auth::check() ? 'sidebar-toggled' : '' }}">
    <div id="app">
        @auth
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="fas fa-warehouse"></i> INVPUSDA
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('dashboard.index') }}" class="list-group-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('barang.index') }}" class="list-group-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i> Manajemen Barang
                </a>
                <a href="{{ route('requests.index') }}" class="list-group-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Permintaan Barang
                </a>
                <a href="{{ route('zoom.requests.index') }}" class="list-group-item {{ request()->routeIs('zoom.requests.*') ? 'active' : '' }}">
                    <i class="fas fa-video"></i> Permintaan Zoom
                </a>
                <a href="{{ route('transaksi.index') }}" class="list-group-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i> Riwayat Transaksi
                </a>
                
                @if(Auth::user()->role === 'super_admin')
                <div class="border-top my-3"></div>
                <a href="{{ route('super.users.index') }}" class="list-group-item {{ request()->routeIs('super.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Manajemen User
                </a>
                @endif
                
                <div class="border-top my-3"></div>
                <a href="{{ route('template.index') }}" class="list-group-item {{ request()->routeIs('template.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Template
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="list-group-item text-left border-0 bg-transparent text-white w-100" style="cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-md navbar-light bg-white">
                <div class="container-fluid">
                    @auth
                    <button class="btn btn-link d-md-none mr-3" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    @else
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <i class="fas fa-warehouse"></i> INVPUSDA
                    </a>
                    @endauth

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            @guest
                            <li><a class="nav-link" href="{{ url('/') }}">Halaman Utama</a></li>
                            @endguest
                        </ul>

                        <ul class="navbar-nav ml-auto">
                            @guest
                            <li><a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login Admin
                            </a></li>
                            @else
                            <li class="nav-item dropdown no-arrow">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                        {{ Auth::user()->name }}
                                        @if(Auth::user()->role === 'super_admin')
                                        <span class="badge badge-primary">Super Admin</span>
                                        @elseif(Auth::user()->role === 'admin_barang')
                                        <span class="badge badge-info">Admin Barang</span>
                                        @endif
                                    </span>
                                    <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff" width="32" height="32">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                    <div class="dropdown-header text-center pb-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff&size=64" class="rounded-circle mb-2" width="64" height="64">
                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                        <i class="fas fa-tachometer-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <!-- <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                        {{ csrf_field() }}
                                    </form> -->
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="py-4">
                <div class="container-fluid">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
