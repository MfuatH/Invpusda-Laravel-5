@extends('layouts.app')

@section('title', 'Edit Barang: ' . $item->name)

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Barang: {{ $item->name }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Barang</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama Barang</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $item->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="bidang">Bidang</label>
                    <select class="form-control" id="bidang" name="bidang_id" required>
                        {{-- Opsi Bidang dari Controller --}}
                        <option value="{{ $item->bidang_id }}" selected>{{ $item->bidang_name }}</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="stock">Stok Saat Ini</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $item->stock) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="unit">Satuan</label>
                        <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit', $item->unit) }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update Barang</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection