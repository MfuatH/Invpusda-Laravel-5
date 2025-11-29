@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 text-gray-800 font-weight-bold mb-0">Dashboard Overview</h1>
            <p class="mb-0 text-muted">Panel kontrol utama sistem inventaris.</p>
        </div>
    </div>

    {{-- 1. STATISTIK CARDS (SOLID COLOR & TEXT WHITE) --}}
    <div class="row mb-4">
        {{-- Total Barang (BIRU) --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary text-white shadow h-100 py-2 border-0 card-wave">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-white text-xs font-weight-bold text-uppercase mb-1">Total Barang</div>
                            <div class="h3 mb-0 font-weight-bold text-white">{{ $data['totalItems'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-boxes fa-3x text-white-50"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Req Barang (KUNING) --}}
        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_barang')
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning text-white shadow h-100 py-2 border-0 card-wave">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-white text-xs font-weight-bold text-uppercase mb-1">Req. Barang</div>
                            <div class="h3 mb-0 font-weight-bold text-white">{{ $data['totalRequests'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-clipboard-list fa-3x text-white-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Req Zoom (BIRU MUDA) --}}
        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_barang')
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info text-white shadow h-100 py-2 border-0 card-wave">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-white text-xs font-weight-bold text-uppercase mb-1">Req. Zoom</div>
                            <div class="h3 mb-0 font-weight-bold text-white">{{ $data['totalZoomRequests'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-video fa-3x text-white-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Req Mobil (UNGU) --}}
        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_barang')
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-purple text-white shadow h-100 py-2 border-0 card-wave">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-white text-xs font-weight-bold text-uppercase mb-1">Req. Mobil</div>
                            <div class="h3 mb-0 font-weight-bold text-white">{{ $data['totalKendaraanRequests'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-car fa-3x text-white-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Req Catering (MERAH) --}}
        @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin_barang' && Auth::user()->bidang && strtolower(Auth::user()->bidang->nama) === 'sekretariat'))
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-danger text-white shadow h-100 py-2 border-0 card-wave">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-white text-xs font-weight-bold text-uppercase mb-1">Req. Catering</div>
                            <div class="h3 mb-0 font-weight-bold text-white">{{ $data['totalCateringRequests'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-utensils fa-3x text-white-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Total Users (HIJAU) --}}
        @if(Auth::user()->role === 'super_admin')
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success text-white shadow h-100 py-2 border-0 card-wave">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-white text-xs font-weight-bold text-uppercase mb-1">Total Pengguna</div>
                            <div class="h3 mb-0 font-weight-bold text-white">{{ $data['totalUsers'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-users fa-3x text-white-50"></i></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- 2. AKSES CEPAT --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body py-3">
            <h6 class="font-weight-bold text-gray-800 mb-3"><i class="fas fa-rocket text-secondary mr-2"></i>Akses Cepat</h6>
            <div class="row text-center">
                <div class="col-md-2 col-4 mb-2">
                    <a href="{{ route('requests.index') }}" class="btn btn-outline-warning btn-block shadow-sm py-2">
                        <i class="fas fa-box d-block mb-1 fa-lg"></i> Appr. Barang
                    </a>
                </div>
                <div class="col-md-2 col-4 mb-2">
                    <a href="{{ route('zoom.requests.index') }}" class="btn btn-outline-info btn-block shadow-sm py-2">
                        <i class="fas fa-video d-block mb-1 fa-lg"></i> Appr. Zoom
                    </a>
                </div>
                <div class="col-md-2 col-4 mb-2">
                    <a href="{{ route('approvals.kendaraan') }}" class="btn btn-outline-purple btn-block shadow-sm py-2">
                        <i class="fas fa-car d-block mb-1 fa-lg"></i> Appr. Mobil
                    </a>
                </div>
                @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin_barang' && Auth::user()->bidang && strtolower(Auth::user()->bidang->nama) === 'sekretariat'))
                <div class="col-md-2 col-4 mb-2">
                    <a href="{{ route('catering.index') }}" class="btn btn-outline-danger btn-block shadow-sm py-2">
                        <i class="fas fa-utensils d-block mb-1 fa-lg"></i> Appr. Makan
                    </a>
                </div>
                @endif
                @if(Auth::user()->role === 'super_admin')
                <div class="col-md-2 col-4 mb-2">
                    <a href="{{ route('super.users.index') }}" class="btn btn-outline-success btn-block shadow-sm py-2">
                        <i class="fas fa-users d-block mb-1 fa-lg"></i> Users
                    </a>
                </div>
                <div class="col-md-2 col-4 mb-2">
                    <a href="{{ route('template.index') }}" class="btn btn-outline-secondary btn-block shadow-sm py-2">
                        <i class="fas fa-cog d-block mb-1 fa-lg"></i> Setting
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 3. KONTEN UTAMA (TABEL TRANSAKSI & BARANG BARU) --}}
    <div class="row">
        
        {{-- A. TABEL RIWAYAT TRANSAKSI (STYLE TABEL BORDERED) --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4 h-100 border-0">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
                    <a href="{{ route('transaksi.index') }}" class="small font-weight-bold text-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        {{-- TABEL DENGAN BORDER & STRIPED (Garis-garis) --}}
                        <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                            <thead class="bg-light text-dark">
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="30%">Nama Barang</th>
                                    <th width="20%">Peminjam</th>
                                    <th width="10%">Jml</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recentTransactions'] as $index => $t)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center small">
                                        {{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="font-weight-bold text-dark">
                                        {{ $t->item->nama_barang ?? '-' }}
                                    </td>
                                    <td class="small">
                                        {{ $t->user->name ?? 'System' }}
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        {{ $t->jumlah }}
                                    </td>
                                    <td class="text-center">
                                        @if($t->tipe == 'masuk')
                                            <span class="badge badge-success px-2 py-1">Masuk</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">Keluar</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- B. BARANG TERBARU (STYLE LIST RAPI) --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 h-100 border-0">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Barang Terbaru</h6>
                    <a href="{{ route('barang.index') }}" class="small font-weight-bold text-success">Lihat Semua</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($data['recentItems'] as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-3 py-3">
                        <div class="d-flex align-items-center">
                            {{-- Ikon Kotak Hijau --}}
                            <div class="btn btn-sm btn-light text-success border mr-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                <i class="fas fa-box fa-lg"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark" style="line-height: 1.2;">{{ \Illuminate\Support\Str::limit($item->nama_barang, 20) }}</div>
                                <div class="small text-muted mt-1">Stok: <span class="font-weight-bold text-dark">{{ $item->jumlah }}</span> Unit</div>
                            </div>
                        </div>
                        <div>
                            @if($item->jumlah > 0)
                                <span class="badge badge-soft-success px-2 py-1 border border-success">Ready</span>
                            @else
                                <span class="badge badge-soft-danger px-2 py-1 border border-danger">Habis</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted small">Belum ada barang baru.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- 4. TABEL DOKUMEN (FULL WIDTH & BORDERED) --}}
    @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin_barang' && Auth::user()->bidang && strtolower(Auth::user()->bidang->nama) === 'sekretariat'))
    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">Dokumen Terbaru</h6>
            <a href="{{ route('documents.index') }}" class="btn btn-sm btn-dark">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Pengunggah</th>
                            <th width="45%">Nama Dokumen</th>
                            <th width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['recentDocuments'] ?? [] as $index => $doc)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center small">
                                {{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y H:i') }}
                            </td>
                            <td>
                                <i class="fas fa-user-circle text-muted mr-1"></i> {{ $doc->pengunggah ?? 'User' }}
                            </td>
                            <td>
                                <i class="fas fa-file-pdf text-danger mr-2"></i> 
                                <span class="text-dark font-weight-bold">{{ \Illuminate\Support\Str::limit($doc->file_original_name ?? $doc->file_laporan, 60) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-secondary px-3 py-1">{{ ucfirst($doc->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada dokumen terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    /* CUSTOM COLORS */
    .bg-purple { background-color: #6f42c1 !important; }
    .btn-outline-purple { color: #6f42c1; border-color: #6f42c1; }
    .btn-outline-purple:hover { color: #fff; background-color: #6f42c1; border-color: #6f42c1; }

    /* SOFT BADGE COLORS */
    .badge-soft-success { background-color: #e6fffa; color: #047857; }
    .badge-soft-danger { background-color: #fef2f2; color: #b91c1c; }

    /* CARD STYLES */
    .card { border-radius: 0.5rem; overflow: hidden; }
    .card-wave { transition: transform 0.2s; }
    .card-wave:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; }

    /* TABLE STYLES */
    .table-bordered th, .table-bordered td { border: 1px solid #dee2e6 !important; vertical-align: middle; }
    .table thead th { background-color: #f8f9fc; color: #5a5c69; font-weight: 700; border-bottom: 2px solid #e3e6f0; }
</style>
@endpush