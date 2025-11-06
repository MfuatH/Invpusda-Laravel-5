@extends('layouts.app') 

@section('title', $title ?? 'Dashboard Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
        <a href="{{ route('barang.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang Baru
        </a>
    </div>

    <!-- Content Row - Quick Stats -->
    <div class="row">
        <!-- Total Barang Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Barang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalItems'] ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('barang.index') }}" class="stretched-link"></a>
            </div>
        </div>

        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_barang')
        <!-- Permintaan Barang Pending Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Permintaan Barang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalRequests'] ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('requests.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Permintaan Zoom Pending Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Permintaan Zoom</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalZoomRequests'] ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-video fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('zoom.requests.index') }}" class="stretched-link"></a>
            </div>
        </div>
        @endif

        <!-- Total Users Card (Super Admin Only) -->
        @if (Auth::user()->role === 'super_admin')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pengguna</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalUsers'] ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('super.users.index') }}" class="stretched-link"></a>
            </div>
        </div>
        @endif
    </div>

    <!-- Content Row - Recent Items and Requests -->
    <div class="row">
        <!-- Recent Items Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Barang Terbaru</h6>
                </div>
                <div class="card-body">
                    @if(isset($data['recentItems']) && count($data['recentItems']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recentItems'] ?? [] as $item)
                                <tr>
                                    <td>{{ $item->nama_barang ?? '-' }}</td>
                                    <td>{{ $item->jumlah ?? 0 }}</td>
                                    <td>
                                        @php
                                            $jumlah = $item->jumlah ?? 0;
                                        @endphp
                                        <span class="badge badge-{{ $jumlah > 0 ? 'success' : 'danger' }}">
                                            {{ $jumlah > 0 ? 'Tersedia' : 'Kosong' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Belum ada barang</p>
                    @endif
                    <div class="text-right mt-3">
                        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permintaan & Approval Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Permintaan & Persetujuan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <a href="{{ route('requests.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-clipboard-check fa-sm mr-2"></i>Approval Barang
                            </a>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <a href="{{ route('zoom.requests.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-video fa-sm mr-2"></i>Approval Zoom
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Additional Features -->
    <div class="row">
        <!-- Transactions History Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi Terbaru</h6>
                </div>
                <div class="card-body">
                    @if(isset($data['recentTransactions']) && count($data['recentTransactions']) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recentTransactions'] as $transaction)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->item->nama_barang }}</td>
                                    <td>{{ $transaction->jumlah }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->tipe == 'masuk' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaction->tipe) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Belum ada transaksi</p>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-primary btn-sm">
                            Lihat Semua Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings & Tools Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan & Tools</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('template.index') }}" class="btn btn-light btn-block text-left">
                            <i class="fas fa-file-alt fa-sm mr-2"></i>Template Pesan
                        </a>
                    </div>
                    
                    @if(Auth::user()->role === 'super_admin')
                    <div class="mb-3">
                        <a href="{{ route('super.users.index') }}" class="btn btn-light btn-block text-left">
                            <i class="fas fa-users-cog fa-sm mr-2"></i>Manajemen User
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card .stretched-link::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
        content: "";
    }
    .btn-light {
        background-color: #f8f9fc;
        border-color: #eaecf4;
    }
    .btn-light:hover {
        background-color: #eaecf4;
        border-color: #dddfeb;
    }
    .table-sm td, .table-sm th {
        padding: 0.5rem;
    }
    .badge {
        font-size: 85%;
    }
    .quick-stats .card {
        transition: transform .2s;
    }
    .quick-stats .card:hover {
        transform: translateY(-3px);
    }
</style>
@endpush