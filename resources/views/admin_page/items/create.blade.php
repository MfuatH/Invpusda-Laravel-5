@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-plus-circle text-success mr-2"></i> Tambah Barang Baru
        </h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST">
                {{  csrf_field()  }}

                <div class="form-group mb-3">
                    <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="nama_barang" 
                        id="nama_barang" 
                        class="form-control {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                        value="{{ old('nama_barang') }}" 
                        required
                    >
                    @if ($errors->has('nama_barang'))
                        <div class="invalid-feedback">{{ $errors->first('nama_barang') }}</div>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah">Jumlah Awal <span class="text-danger">*</span></label>
                    <input 
                        type="number" 
                        name="jumlah" 
                        id="jumlah" 
                        class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}"
                        value="{{ old('jumlah') }}" 
                        min="0" 
                        required
                    >
                    @if ($errors->has('jumlah'))
                        <div class="invalid-feedback">{{ $errors->first('jumlah') }}</div>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label for="satuan">Satuan <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="satuan" 
                        id="satuan"
                        class="form-control {{ $errors->has('satuan') ? 'is-invalid' : '' }}"
                        value="{{ old('satuan') }}" 
                        required
                    >
                    @if ($errors->has('satuan'))
                        <div class="invalid-feedback">{{ $errors->first('satuan') }}</div>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label for="lokasi">Lokasi Penyimpanan</label>
                    <input 
                        type="text" 
                        name="lokasi" 
                        id="lokasi"
                        class="form-control {{ $errors->has('lokasi') ? 'is-invalid' : '' }}"
                        value="{{ old('lokasi') }}"
                    >
                    @if ($errors->has('lokasi'))
                        <div class="invalid-feedback">{{ $errors->first('lokasi') }}</div>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <label for="keterangan">Keterangan</label>
                    <textarea 
                        name="keterangan" 
                        id="keterangan" 
                        rows="3"
                        class="form-control {{ $errors->has('keterangan') ? 'is-invalid' : '' }}"
                    >{{ old('keterangan') }}</textarea>
                    @if ($errors->has('keterangan'))
                        <div class="invalid-feedback">{{ $errors->first('keterangan') }}</div>
                    @endif
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
