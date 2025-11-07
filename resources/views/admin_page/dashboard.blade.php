@extends('layouts.app')

@section('title', $title ?? 'Dashboard Admin')

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
        <a href="{{ route('barang.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang Baru
        </a>
    </div>

    <!-- Statistik Cepat -->
    <div class="row g-3">
        <!-- Total Barang -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalItems'] ?? '0' }}</div>
                    </div>
                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                </div>
                <a href="{{ route('barang.index') }}" class="stretched-link"></a>
            </div>
        </div>

        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_barang')
        <!-- Permintaan Barang -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Permintaan Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalRequests'] ?? '0' }}</div>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
                <a href="{{ route('requests.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <!-- Permintaan Zoom -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Permintaan Zoom</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalZoomRequests'] ?? '0' }}</div>
                    </div>
                    <i class="fas fa-video fa-2x text-gray-300"></i>
                </div>
                <a href="{{ route('zoom.requests.index') }}" class="stretched-link"></a>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'super_admin')
        <!-- Total Pengguna -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pengguna</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalUsers'] ?? '0' }}</div>
                    </div>
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
                <a href="{{ route('super.users.index') }}" class="stretched-link"></a>
            </div>
        </div>
        @endif
    </div>

    <!-- Baris: Barang & Transaksi -->
    <div class="row mt-4">
        <!-- Barang Terbaru -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Barang Terbaru</h6>
                </div>
                <div class="card-body">
                    @if(isset($data['recentItems']) && count($data['recentItems']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recentItems'] as $item)
                                <tr>
                                    <td>{{ $item->nama_barang ?? '-' }}</td>
                                    <td>{{ $item->jumlah ?? 0 }}</td>
                                    <td>
                                        <span class="badge badge-{{ ($item->jumlah ?? 0) > 0 ? 'success' : 'danger' }}">
                                            {{ ($item->jumlah ?? 0) > 0 ? 'Tersedia' : 'Kosong' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted my-3">Belum ada barang</p>
                    @endif
                    <div class="text-right mt-3">
                        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi Terbaru</h6>
                </div>
                <div class="card-body">
                    @if(isset($data['recentTransactions']) && count($data['recentTransactions']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="thead-light">
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
                    <p class="text-center text-muted my-3">Belum ada transaksi</p>
                    @endif
                    <div class="text-right mt-3">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-primary btn-sm">Lihat Semua Transaksi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris: Approval dan Tools -->
    <div class="row mt-2">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Permintaan & Persetujuan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <a href="{{ route('requests.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-clipboard-check mr-2"></i>Approval Barang
                            </a>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <a href="{{ route('zoom.requests.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-video mr-2"></i>Approval Zoom
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tools -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan & Tools</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('template.index') }}" class="btn btn-light btn-block text-left mb-2">
                        <i class="fas fa-file-alt mr-2"></i>Template Pesan
                    </a>
                    @if(Auth::user()->role === 'super_admin')
                    <a href="{{ route('super.users.index') }}" class="btn btn-light btn-block text-left">
                        <i class="fas fa-users-cog mr-2"></i>Manajemen User
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .table th, .table td {
        vertical-align: middle !important;
    }
    .table thead {
        background-color: #f8f9fc;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.6em;
    }
    .btn-light {
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        color: #4e73df;
    }
    .btn-light:hover {
        background-color: #e2e6ea;
        color: #2e59d9;
    }
    .stretched-link::after {
        position: absolute;
        content: "";
        top: 0; right: 0; bottom: 0; left: 0;
    }
</style>
@endpush
