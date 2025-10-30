@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid">

    {{-- 🔹 Judul Halaman --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="font-weight-bold text-dark">Riwayat Transaksi</h4>
    </div>

    {{-- 🔹 Card Utama --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Tombol Export --}}
            <div class="d-flex justify-content-between mb-3">
                {{-- Anda harus mendefinisikan route 'export.transactions' di web.php --}}
                <a href="#" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export ke Excel
                </a>
            </div>

            {{-- 🔹 Tabel Riwayat Barang --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" style="width:100%;">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Tipe</th>
                            <th>Dicatat Oleh (Admin)</th>
                            <th>Pemohon (Jika Keluar)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PERBAIKAN: Menggunakan variabel $transactions --}}
                        @forelse($transactions ?? [] as $t)
                            <tr>
                                {{-- 1. Tanggal Transaksi --}}
                                <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y H:i') }}</td>
                                
                                {{-- 2. Nama Barang --}}
                                <td>{{ $t->item->nama_barang ?? 'Barang Dihapus' }}</td>
                                
                                {{-- 3. Jumlah --}}
                                <td class="font-weight-bold">{{ $t->jumlah }}</td>
                                
                                {{-- 4. Tipe (Masuk/Keluar) --}}
                                <td>
                                    @if(strtolower($t->tipe) == 'masuk')
                                        <span class="badge bg-success text-white px-3 py-2">Masuk</span>
                                    @elseif(strtolower($t->tipe) == 'keluar')
                                        <span class="badge bg-danger text-white px-3 py-2">Keluar</span>
                                    @else
                                        <span class="badge bg-secondary text-white px-3 py-2">-</span>
                                    @endif
                                </td>
                                
                                {{-- 5. Dicatat Oleh (Admin) --}}
                                <td>
                                    <small>{{ $t->user->name ?? 'System' }}</small>
                                    <br><span class="badge badge-secondary">{{ $t->user->bidang->nama ?? 'Super Admin' }}</span>
                                </td>
                                
                                {{-- 6. Pemohon (Jika ada) --}}
                                <td>
                                    @if ($t->tipe === 'keluar' && $t->request)
                                        <small>{{ $t->request->nama_pemohon ?? '-' }}</small>
                                        <br><span class="badge badge-info">{{ $t->request->bidang->nama ?? '-' }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada data transaksi barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PERBAIKAN: Menambahkan Paginasi --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links() }}
            </div>
            
        </div>
    </div>

</div>

{{-- 🔹 Styling tambahan --}}
@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle !important;
    }
    .table thead th {
        background-color: #2c3e50 !important;
        color: white !important;
        border: none;
    }
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    .badge {
        border-radius: 10px;
        font-size: 0.8rem;
    }
    .bg-success { background-color: #28a745 !important; }
    .bg-danger { background-color: #dc3545 !important; }
    .bg-secondary { background-color: #6c757d !important; }
    .bg-info { background-color: #17a2b8 !important; }

    /* Fix untuk badge dan teks di cell */
    td {
        line-height: 1.5;
    }
</style>
@endpush

@endsection