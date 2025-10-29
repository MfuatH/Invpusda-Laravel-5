@extends('layouts.app')

@section('title', 'Manajemen Response Cepat')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Response Cepat</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Response Otomatis (Whatsapp/Email)</h6>
        </div>
        <div class="card-body">
            @if (Auth::user()->role === 'admin_barang')
            <div class="alert alert-info">Anda hanya dapat mengelola response untuk Bidang Anda.</div>
            @endif
            
            {{-- Tabel atau Form untuk mengelola Response --}}
            <p>Form dan tabel untuk mengelola teks Response Cepat di sini. Contoh: Response sukses, response ditolak, dll.</p>
            <p>Variabel yang bisa digunakan: [NAMA_PEMOHON], [LINK_ZOOM], [NAMA_BARANG], dll.</p>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tipe Response</th>
                        <th>Status</th>
                        <th>Isi Pesan (Preview)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Approval Barang (WA)</td>
                        <td>Aktif</td>
                        <td>Yth. [NAMA_PEMOHON], permintaan barang Anda...</td>
                        <td><button class="btn btn-warning btn-sm">Edit</button></td>
                    </tr>
                    {{-- Data Response lainnya --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection