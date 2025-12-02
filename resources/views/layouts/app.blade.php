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
            overflow-x: hidden;
        }

        /* --- SIDEBAR WRAPPER --- */
        #sidebar-wrapper {
            height: 100vh;
            width: var(--sidebar-width);
            margin-left: -var(--sidebar-width);
            transition: margin 0.25s ease-out;
            /* Gradient Background Biru */
            background: linear-gradient(180deg, rgb(58, 174, 237) 0%, rgb(37, 107, 215) 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Custom Scrollbar Sidebar */
        #sidebar-wrapper::-webkit-scrollbar { width: 6px; }
        #sidebar-wrapper::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.1); }
        #sidebar-wrapper::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); border-radius: 3px; }
        #sidebar-wrapper::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.5); }

        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: transparent;
            flex-shrink: 0;
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

        /* STYLE MENU UTAMA */
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
            text-decoration: none;
        }

        #sidebar-wrapper .list-group-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Menu Utama Aktif */
        #sidebar-wrapper .list-group-item.active {
            color: #fff;
            background-color: #3C80E0;
            border-radius: 8px;
            margin-left: 0.75rem;
            margin-right: 0.75rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding-left: 1.5rem; 
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

        /* BADGE NOTIFIKASI */
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

        /* Panah Dropdown Parent (Sebelah Kanan) */
        .dropdown-arrow {
            margin-left: 5px;
            font-size: 0.7rem;
            transition: transform 0.2s ease;
        }

        /* Tombol Logout */
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
            flex-shrink: 0;
        }

        .logout-button:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        /* CSS Submenu Handling - Rotasi Panah Parent */
        #sidebar-wrapper .list-group-item[data-toggle="collapse"] {
            justify-content: space-between;
        }

        #sidebar-wrapper .list-group-item[aria-expanded="true"] .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* ========================================================== */
        /* --- STYLE SUBMENU (DROPDOWN) --- */
        /* ========================================================== */
        .submenu-collapse {
            background: rgba(0, 0, 0, 0.15); /* Background sedikit lebih gelap */
            margin-bottom: 5px;
        }

        .submenu-collapse .list-group-item {
            padding-left: 3.5rem; /* INDENTASI: Masuk ke dalam */
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            margin: 0;
            border-radius: 0;
            
            font-size: 0.85rem; /* Font lebih kecil */
            color: rgba(255, 255, 255, 0.5) !important; /* Warna Abu-abu (Transparan) saat diam */
            background: transparent !important;
            font-weight: 400;
            box-shadow: none !important;
            border-left: none !important;
        }

        /* Efek Hover Submenu */
        .submenu-collapse .list-group-item:hover {
            color: #ffffff !important; /* Jadi putih saat di-hover */
            background: rgba(255, 255, 255, 0.05) !important;
            text-decoration: none;
        }

        /* Submenu Aktif (Setelah Dipencet) */
        .submenu-collapse .list-group-item.active {
            color: #ffffff !important; /* Putih Terang Solid */
            font-weight: 600;          /* Sedikit tebal */
            background: transparent !important;
        }
        
        /* Ikon Panah Kecil di Submenu */
        .submenu-icon {
            font-size: 0.75rem;
            margin-right: 10px;
            opacity: 0.7;
        }
        .submenu-collapse .list-group-item:hover .submenu-icon,
        .submenu-collapse .list-group-item.active .submenu-icon {
            opacity: 1;
        }

        /* ========================================================== */

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
            <a href="{{ route('dashboard.index') }}" class="list-group-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <i class="fas fa-th-large menu-icon"></i> Dashboard
            </a>

            <a href="{{ route('barang.index') }}" class="list-group-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <i class="fas fa-box menu-icon"></i> Manajemen Barang
            </a>
            
            @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin_barang' && Auth::user()->bidang && strtolower(Auth::user()->bidang->nama) === 'sekretariat'))
            <a href="{{ route('documents.index') }}" class="list-group-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt menu-icon"></i> Manajemen Dokumen
            </a>
            @endif

            <a href="{{ route('requests.index') }}" class="list-group-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                <i class="fas fa-check-circle menu-icon"></i> Approval Barang
                @php
                    $totalReq = $notifCounts['requests'] ?? ($data['totalRequests'] ?? 0);
                @endphp
                @if($totalReq > 0)
                    <span class="badge-notification">{{ $totalReq }}</span>
                @endif
            </a>

            @php
                $isZoomMenuActive = request()->routeIs('zoom.requests.index') || request()->routeIs('template.index');
                $totalZoom = $notifCounts['zoom'] ?? ($data['totalZoomRequests'] ?? 0);
            @endphp

            <a href="#zoomSubmenu" data-toggle="collapse" aria-expanded="{{ $isZoomMenuActive ? 'true' : 'false' }}" class="list-group-item {{ $isZoomMenuActive ? 'active' : '' }}">
                <i class="fas fa-video menu-icon"></i>
                Approval Zoom
                @if($totalZoom > 0)
                    <span class="badge-notification">{{ $totalZoom }}</span>
                @endif
                <span class="ml-auto"><i class="fas fa-chevron-down dropdown-arrow ml-2"></i></span>
            </a>

            <div class="collapse submenu-collapse {{ $isZoomMenuActive ? 'show' : '' }}" id="zoomSubmenu">
                <div class="list-group list-group-flush">
                    <a href="{{ route('zoom.requests.index') }}" class="list-group-item {{ request()->routeIs('zoom.requests.index') ? 'active' : '' }}">
                        <i class="fas fa-caret-right submenu-icon"></i> Zoom Approve
                    </a>
                    <a href="{{ route('template.index') }}" class="list-group-item {{ request()->routeIs('template.index') ? 'active' : '' }}">
                        <i class="fas fa-caret-right submenu-icon"></i> Master Pesan
                    </a>
                </div>
            </div>

            @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin_barang' && Auth::user()->bidang && strtolower(Auth::user()->bidang->nama) === 'sekretariat'))
            <a href="{{ route('catering.index') }}" class="list-group-item {{ request()->routeIs('catering.*') ? 'active' : '' }}">
                <i class="fas fa-utensils menu-icon"></i> Approve Catering
                @php
                    $totalCatering = $notifCounts['catering'] ?? ($data['totalCateringRequests'] ?? 0);
                @endphp
                @if($totalCatering > 0)
                    <span class="badge-notification">{{ $totalCatering }}</span>
                @endif
            </a>
            @endif

            @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin_barang' && Auth::user()->bidang && strtolower(Auth::user()->bidang->nama) === 'sekretariat'))

                @php
                    $isKendaraanActive = request()->routeIs('kendaraan.index') || request()->routeIs('approvals.kendaraan');
                    $totalKendaraan = $notifCounts['kendaraan'] ?? ($data['totalKendaraanRequests'] ?? 0);
                @endphp

                <a href="#kendaraanSubmenu" data-toggle="collapse" aria-expanded="{{ $isKendaraanActive ? 'true' : 'false' }}" class="list-group-item {{ $isKendaraanActive ? 'active' : '' }}">
                    <i class="fas fa-car menu-icon"></i>
                    Approve Kendaraan
                    @if($totalKendaraan > 0)
                        <span class="badge-notification">{{ $totalKendaraan }}</span>
                    @endif
                    <span class="ml-auto"><i class="fas fa-chevron-down dropdown-arrow ml-2"></i></span>
                </a>

                <div class="collapse submenu-collapse {{ $isKendaraanActive ? 'show' : '' }}" id="kendaraanSubmenu">
                    <div class="list-group list-group-flush">

                        <a href="{{ route('approvals.kendaraan') }}" class="list-group-item {{ request()->routeIs('approvals.kendaraan') ? 'active' : '' }}">
                            <i class="fas fa-caret-right submenu-icon"></i> Approval Peminjaman
                        </a>

                        <a href="{{ route('kendaraan.index') }}" class="list-group-item {{ request()->routeIs('kendaraan.index') ? 'active' : '' }}">
                            <i class="fas fa-caret-right submenu-icon"></i> Daftar Kendaraan
                        </a>

                    </div>
                </div>
            @endif

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