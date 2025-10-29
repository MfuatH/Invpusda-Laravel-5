@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Barang Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Input Barang</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Barang</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="bidang">Bidang</label>
                    <select class="form-control" id="bidang" name="bidang_id" required>
                        <option value="">Pilih Bidang</option>
                        {{-- Opsi Bidang dari Controller --}}
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="stock">Stok Awal</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="0" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="unit">Satuan</label>
                        <input type="text" class="form-control" id="unit" name="unit" placeholder="Pcs/Unit/Kotak" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Barang</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection