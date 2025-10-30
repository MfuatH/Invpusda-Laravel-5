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
                <button class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export ke Excel
                </button>
            </div>

            {{-- 🔹 Tabel Riwayat Barang --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" style="width:100%;">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Tipe</th>
                            <th>Peminta/Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangLogs ?? [] as $log)
                            <tr>
                                <td>{{ $log->tanggal ?? '-' }}</td>
                                <td>{{ $log->kode_barang ?? '-' }}</td>
                                <td>{{ $log->nama_barang ?? '-' }}</td>
                                <td>{{ $log->jumlah ?? '-' }}</td>
                                <td>
                                    @if(isset($log->tipe) && strtolower($log->tipe) == 'masuk')
                                        <span class="badge bg-success text-white px-3 py-2">Masuk</span>
                                    @elseif(isset($log->tipe) && strtolower($log->tipe) == 'keluar')
                                        <span class="badge bg-danger text-white px-3 py-2">Keluar</span>
                                    @else
                                        <span class="badge bg-secondary text-white px-3 py-2">-</span>
                                    @endif
                                </td>
                                <td>{{ $log->oleh ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    Tidak ada data transaksi barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
        font-size: 0.85rem;
    }
</style>
@endpush

@endsection
