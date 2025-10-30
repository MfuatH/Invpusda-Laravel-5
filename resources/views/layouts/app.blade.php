<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - Admin</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: var(--sidebar-width);
            margin-left: -var(--sidebar-width);
            transition: margin 0.25s ease-out;
            background: linear-gradient(180deg, rgb(58, 174, 237) 0%, rgb(37, 107, 215) 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: transparent;
        }

        .sidebar-logo {
            max-width: 150px;
            margin-bottom: 1rem;
        }

        .sidebar-welcome-text {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        #sidebar-wrapper .list-group {
            width: 100%;
            padding: 1rem 0;
            flex-grow: 1;
        }

        #sidebar-wrapper .list-group-item {
            border: none;
            padding: 0.9rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            background: transparent;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: background-color 0.2s ease, color 0.2s ease;
            margin: 0.2rem 0;
        }

        #sidebar-wrapper .list-group-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        #sidebar-wrapper .list-group-item.active {
            color: #fff;
            background-color: #3C80E0;
            border-radius: 8px;
            margin-left: 0.75rem;
            margin-right: 0.75rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #sidebar-wrapper .list-group-item.active {
             padding-left: calc(1.5rem - 0.75rem);
        }


        #sidebar-wrapper .list-group-item i.menu-icon {
            margin-right: 0.8rem;
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
        }
         #sidebar-wrapper .list-group-item.active i.menu-icon {
             color: #fff;
         }

        .badge-notification {
            background-color: #e74a3b;
            color: white;
            border-radius: 50%;
            padding: 0.15em 0.5em;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: auto;
            line-height: 1;
            height: 18px;
            min-width: 18px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        .dropdown-arrow {
            margin-left: 5px;
            font-size: 0.7rem;
            transition: transform 0.2s ease;
        }

        .logout-button {
            border: none;
            padding: 0.9rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            background: transparent;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: background-color 0.2s ease, color 0.2s ease;
            margin-top: auto;
            margin-bottom: 1rem;
            width: calc(100% - 1.5rem);
            margin-left: 0.75rem;
            margin-right: 0.75rem;
            text-align: left;
            cursor: pointer;
        }
        .logout-button:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        /* CSS Submenu */
        #sidebar-wrapper .list-group-item[data-toggle="collapse"] {
            justify-content: space-between;
        }
        #sidebar-wrapper .list-group-item[aria-expanded="true"] .dropdown-arrow {
            transform: rotate(180deg);
        }
        .submenu-collapse {
            background: rgba(0, 0, 0, 0.15);
        }
        .submenu-collapse .list-group-item {
            padding-left: 3.5rem; /* <-- DIKECILKAN SUBMENU: Indentasi sedikit ditambah */
            padding-top: 0.5rem; /* <-- DIKECILKAN SUBMENU */
            padding-bottom: 0.5rem; /* <-- DIKECILKAN SUBMENU */
            font-size: 0.8rem; /* <-- DIKECILKAN SUBMENU */
            margin: 0;
            font-weight: 400; /* <-- DIKECILKAN SUBMENU: Font lebih tipis */
        }
        /* Style active submenu dibuat lebih simpel */
        .submenu-collapse .list-group-item.active {
            background: none;
            color: #fff;
            font-weight: 600; /* <-- DIKECILKAN SUBMENU: Bold biasa */
            margin: 0;
            box-shadow: none;
            padding-left: 3.5rem; /* <-- DIKECILKAN SUBMENU: Samakan indentasi */
        }
        .submenu-collapse .list-group-item:hover {
             background: rgba(255, 255, 255, 0.15);
        }


        #page-content-wrapper {
            min-width: 100vw;
            margin-left: 0;
            transition: margin 0.25s ease-out;
        }

        body.sidebar-toggled #sidebar-wrapper {
            margin-left: 0;
        }

        body.sidebar-toggled #page-content-wrapper {
            margin-left: var(--sidebar-width);
            min-width: calc(100vw - var(--sidebar-width));
        }

        main {
            padding: 1.5rem;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper { margin-left: 0; }
            #page-content-wrapper { min-width: 0; margin-left: var(--sidebar-width); }
        }
        @media (max-width: 767.98px) {
            #page-content-wrapper { margin-left: 0 !important; }
        }
   </style>

    @stack('styles')
    @yield('head')
</head>
<body class="{{ Auth::check() ? 'sidebar-toggled' : '' }}">
    <div id="app" class="d-flex">
        @if (Auth::check())
        <div id="sidebar-wrapper">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo PUSDA" class="sidebar-logo">
                <p class="sidebar-welcome-text">Welcome, {{ Auth::user()->name }}</p>
            </div>

            <div class="list-group list-group-flush">
                @php
                    $routePrefix = Auth::user()->role === 'super_admin' ? 'superadmin.' : 'adminbarang.';
                @endphp

                <a href="{{ route('dashboard.index') }}" class="list-group-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <i class="fas fa-th-large menu-icon"></i> Dashboard
                </a>
                <a href="{{ route('barang.index') }}" class="list-group-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                    <i class="fas fa-box menu-icon"></i> Manajemen Barang
                </a>
                 <a href="{{ route('requests.index') }}" class="list-group-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                    <i class="fas fa-check-circle menu-icon"></i> Approval Barang

                </a>

                @php
                    $isZoomMenuActive = request()->routeIs('zoom.requests.index') || request()->routeIs('template.index');
                @endphp

                <a href="#zoomSubmenu" data-toggle="collapse" aria-expanded="{{ $isZoomMenuActive ? 'true' : 'false' }}" class="list-group-item {{ $isZoomMenuActive ? 'active' : '' }}">
                    <i class="fas fa-video menu-icon"></i>
                    Approval Zoom

                    <span class="ml-auto">
                        <i class="fas fa-chevron-down dropdown-arrow ml-2"></i>
                    </span>
                </a>
                <div class="collapse submenu-collapse {{ $isZoomMenuActive ? 'show' : '' }}" id="zoomSubmenu">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('zoom.requests.index') }}" class="list-group-item {{ request()->routeIs('zoom.requests.index') ? 'active' : '' }}">
                            Zoom Approv
                        </a>
                        <a href="{{ route('template.index') }}" class="list-group-item {{ request()->routeIs('template.index') ? 'active' : '' }}">
                            Master Pesan
                        </a>
                    </div>
                </div>

                 <a href="{{ route('transaksi.index') }}" class="list-group-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                    <i class="fas fa-history menu-icon"></i> Riwayat Transaksi
                </a>

                @if(Auth::user()->role === 'super_admin')
                    <a href="{{ route('super.users.index') }}" class="list-group-item {{ request()->routeIs('super.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users menu-icon"></i> Manajemen User
                    </a>
                @endif
            </div>

            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
                {{ csrf_field() }}
                <button type="submit" class="logout-button">
                    <i class="fas fa-sign-out-alt menu-icon"></i> Log Out
                </button>
            </form>
        </div>
        @endif

        <div id="page-content-wrapper">
            <main>
                <div class="container-fluid">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @stack('scripts')
    @yield('js')
</body>
</html>