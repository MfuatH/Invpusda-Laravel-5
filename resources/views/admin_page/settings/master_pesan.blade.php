@extends('layouts.app')

@section('title', 'Master Template Pesan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Master Template Pesan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengaturan Template Notifikasi Sistem</h6>
        </div>
        <div class="card-body">
            @if (Auth::user()->role === 'admin_barang')
            <div class="alert alert-info">Anda hanya dapat melihat dan mengelola template pesan Bidang Anda (jika diizinkan).</div>
            @endif
            
            {{-- Form dan Daftar Template Pesan --}}
            <p>Di halaman ini, Super Admin dapat mengelola semua template pesan. Admin Barang mungkin hanya dapat melihat atau mengedit template yang relevan dengan bidangnya (misalnya, untuk notifikasi stok).</p>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode Template</th>
                        <th>Deskripsi</th>
                        <th>Saluran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BARANG_APPROVE</td>
                        <td>Notifikasi Persetujuan Permintaan Barang</td>
                        <td>WA/Email</td>
                        <td><button class="btn btn-warning btn-sm">Edit</button></td>
                    </tr>
                    {{-- Data Template lainnya --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection