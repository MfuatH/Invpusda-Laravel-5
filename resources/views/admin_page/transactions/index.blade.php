@extends('layouts.app')

@section('title', 'Riwayat Transaksi & Aktivitas')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Riwayat Transaksi & Aktivitas</h1>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="barang-tab" data-toggle="tab" href="#barang" role="tab">Riwayat Barang</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="zoom-tab" data-toggle="tab" href="#zoom" role="tab">Riwayat Zoom</a>
        </li>
    </ul>
    
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="barang" role="tabpanel" aria-labelledby="barang-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Log Keluar/Masuk Barang</h6>
                </div>
                <div class="card-body">
                    @if (Auth::user()->role === 'admin_barang')
                    <div class="alert alert-info">Hanya menampilkan log transaksi barang Bidang Anda.</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Isi data riwayat transaksi barang --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="zoom" role="tabpanel" aria-labelledby="zoom-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas Link Zoom</h6>
                </div>
                <div class="card-body">
                     @if (Auth::user()->role === 'admin_barang')
                    <div class="alert alert-info">Hanya menampilkan log zoom Bidang Anda.</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal Request</th>
                                    <th>Topik</th>
                                    <th>Status</th>
                                    <th>Approval Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Isi data riwayat zoom --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection