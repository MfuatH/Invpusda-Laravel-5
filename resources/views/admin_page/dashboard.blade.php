@extends('layouts.app') 

@section('title', $title ?? 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-gray-800 font-weight-bold">{{ $title ?? 'Dashboard' }}</h1>
            <p class="mb-0 text-gray-500">Ringkasan data dan aktivitas terbaru.</p>
        </div>
        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-icon-split shadow-sm">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah Barang Baru</span>
        </a>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Barang</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $data['totalItems'] ?? '0' }}</div>
                        </div>
                        <div class="icon-circle bg-primary-soft text-primary">
                            <i class="fas fa-boxes fa-lg"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('barang.index') }}" class="stretched-link"></a>
            </div>
        </div>

        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_barang')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Permintaan Barang</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $data['totalRequests'] ?? '0' }}</div>
                        </div>
                        <div class="icon-circle bg-warning-soft text-warning">
                            <i class="fas fa-clipboard-list fa-lg"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('requests.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Permintaan Zoom</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $data['totalZoomRequests'] ?? '0' }}</div>
                        </div>
                        <div class="icon-circle bg-info-soft text-info">
                            <i class="fas fa-video fa-lg"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('zoom.requests.index') }}" class="stretched-link"></a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Permintaan Catering</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $data['totalCateringRequests'] ?? '0' }}</div>
                        </div>
                        <div class="icon-circle bg-danger-soft text-danger">
                            <i class="fas fa-utensils fa-lg"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('catering.index') }}" class="stretched-link"></a>
            </div>
        </div>
        @endif

        @if (Auth::user()->role === 'super_admin')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pengguna</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $data['totalUsers'] ?? '0' }}</div>
                        </div>
                        <div class="icon-circle bg-success-soft text-success">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('super.users.index') }}" class="stretched-link"></a>
            </div>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Barang Terbaru</h6>
                    <a href="{{ route('barang.index') }}" class="btn btn-sm btn-light text-primary font-weight-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if(isset($data['recentItems']) && count($data['recentItems']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-align-middle mb-0">
                            <thead class="bg-light text-gray-600">
                                <tr>
                                    <th class="border-0">Nama Barang</th>
                                    <th class="border-0">Stok</th>
                                    <th class="border-0 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recentItems'] ?? [] as $item)
                                @php $jumlah = $item->jumlah ?? 0; @endphp
                                <tr>
                                    <td class="font-weight-bold text-gray-700">{{ $item->nama_barang ?? '-' }}</td>
                                    <td>{{ $jumlah }} Unit</td>
                                    <td class="text-center">
                                        <span class="badge badge-pill {{ $jumlah > 0 ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                            {{ $jumlah > 0 ? 'Tersedia' : 'Kosong' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-box-open fa-2x mb-2 text-gray-300"></i>
                        <p>Belum ada barang.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi Terbaru</h6>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-light text-primary font-weight-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if(isset($data['recentTransactions']) && count($data['recentTransactions']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-align-middle mb-0">
                            <thead class="bg-light text-gray-600">
                                <tr>
                                    <th class="border-0">Tanggal</th>
                                    <th class="border-0">Barang</th>
                                    <th class="border-0">Jml</th>
                                    <th class="border-0 text-center">Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recentTransactions'] as $transaction)
                                <tr>
                                    <td class="text-gray-600 small">{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}</td>
                                    <td class="font-weight-bold text-gray-700">{{ $transaction->item->nama_barang }}</td>
                                    <td>{{ $transaction->jumlah }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-pill {{ $transaction->tipe == 'masuk' ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                            {{ ucfirst($transaction->tipe) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-exchange-alt fa-2x mb-2 text-gray-300"></i>
                        <p>Belum ada transaksi.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Dokumen Terbaru</h6>
                    <a href="{{ route('documents.index') }}" class="btn btn-sm btn-light text-primary font-weight-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if(isset($data['recentDocuments']) && count($data['recentDocuments']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-align-middle mb-0">
                            <thead class="bg-light text-gray-600">
                                <tr>
                                    <th class="border-0">Pengunggah</th>
                                    <th class="border-0">Nama File</th>
                                    <th class="border-0">Tanggal</th>
                                    <th class="border-0 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recentDocuments'] as $doc)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle-sm bg-light text-gray-600 mr-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="font-weight-bold text-gray-700">{{ $doc->pengunggah ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">
                                        <i class="fas fa-file-pdf text-danger mr-1"></i>
                                        {{ $doc->file_original_name ?? $doc->file_laporan ?? '-' }}
                                    </td>
                                    <td class="small">{{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-pill {{ $doc->status == 'submitted' ? 'badge-soft-warning' : ($doc->status == 'verified' ? 'badge-soft-success' : 'badge-soft-secondary') }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-4 text-center text-muted">
                        <p>Belum ada dokumen terbaru.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-gray-800">Akses Cepat</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Menu pintas untuk persetujuan dan pengaturan.</p>
                    
                    <div class="row no-gutters">
                        <div class="col-12 mb-2">
                            <a href="{{ route('requests.index') }}" class="btn btn-light btn-block text-left d-flex justify-content-between align-items-center p-3 shadow-sm border-0">
                                <span><i class="fas fa-box text-warning mr-2"></i> Approval Barang</span>
                                <i class="fas fa-chevron-right text-gray-400 small"></i>
                            </a>
                        </div>
                        <div class="col-12 mb-2">
                            <a href="{{ route('zoom.requests.index') }}" class="btn btn-light btn-block text-left d-flex justify-content-between align-items-center p-3 shadow-sm border-0">
                                <span><i class="fas fa-video text-info mr-2"></i> Approval Zoom</span>
                                <i class="fas fa-chevron-right text-gray-400 small"></i>
                            </a>
                        </div>
                        <div class="col-12 mb-2">
                            <a href="{{ route('catering.index') }}" class="btn btn-light btn-block text-left d-flex justify-content-between align-items-center p-3 shadow-sm border-0">
                                <span><i class="fas fa-utensils text-danger mr-2"></i> Approval Catering</span>
                                <i class="fas fa-chevron-right text-gray-400 small"></i>
                            </a>
                        </div>
                    </div>

                    @if(Auth::user()->role === 'super_admin')
                    <hr>
                    <a href="{{ route('template.index') }}" class="btn btn-primary btn-block btn-sm">
                        <i class="fas fa-cog mr-1"></i> Pengaturan Template
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
    /* Custom CSS untuk Dashboard yang Lebih Modern */
    .text-gray-500 { color: #b7b9cc !important; }
    
    /* Hover Effect pada Card Statistik */
    .card-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }

    /* Icon Bubbles */
    .icon-circle {
        height: 3rem;
        width: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-circle-sm {
        height: 2rem;
        width: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Soft Background Colors for Icons & Badges */
    .bg-primary-soft { background-color: rgba(78, 115, 223, 0.1); }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1); }
    .bg-info-soft    { background-color: rgba(54, 185, 204, 0.1); }
    .bg-warning-soft { background-color: rgba(246, 194, 62, 0.1); }
    .bg-danger-soft  { background-color: rgba(231, 74, 59, 0.1); }

    /* Modern Badges (Pills) */
    .badge-pill {
        padding: 0.5em 0.8em;
        font-weight: 600;
    }
    .badge-soft-success { color: #1cc88a; background-color: rgba(28, 200, 138, 0.1); }
    .badge-soft-danger  { color: #e74a3b; background-color: rgba(231, 74, 59, 0.1); }
    .badge-soft-warning { color: #f6c23e; background-color: rgba(246, 194, 62, 0.1); }
    .badge-soft-secondary { color: #858796; background-color: rgba(133, 135, 150, 0.1); }

    /* ========================================================== */
    /* --- PERBAIKAN GAYA TABEL --- */
    /* ========================================================== */
    .table-responsive {
        border: none !important; /* Menghilangkan border luar wrapper */
    }
    .table-align-middle td, .table-align-middle th {
        vertical-align: middle;
        padding: 0.75rem 1rem; /* Menambah padding agar baris lebih lega */
    }
    .table-align-middle thead th {
        border-bottom: 1px solid #e3e6f0; /* Menambah border tipis di bawah header */
        color: #5a5c69; /* Warna teks header yang sedikit lebih gelap */
    }
    .table-align-middle tbody tr {
        border-bottom: 1px solid #f5f5f5; /* Border tipis antar baris */
    }
    .table-align-middle tbody tr:last-child {
        border-bottom: none; /* Hapus border di baris terakhir */
    }
    .table-align-middle.table-hover tbody tr:hover {
        background-color: #fcfcfc;
    }
    /* Button Light Custom */
    .btn-light {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
    }
    .btn-light:hover {
        background: #eaecf4;
    }
</style>
@endpush