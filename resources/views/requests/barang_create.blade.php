@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-boxes"></i> Form Permintaan Barang
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('request.barang.store') }}" method="POST">
                        
                        <div class="form-group">
                            <label for="nama_pemohon">Nama Pemohon</label>
                            <input type="text" class="form-control" id="nama_pemohon" name="nama_pemohon" value="{{ old('nama_pemohon') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="bidang_id">Bidang</label>
                            <select class="form-control" id="bidang_id" name="bidang_id" required>
                                <option value="">Pilih Bidang</option>
                                @foreach($bidang as $id => $nama)
                                    <option value="{{ $id }}" {{ old('bidang_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nip">NIP (Opsional)</label>
                            <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip') }}">
                        </div>

                        <div class="form-group">
                            <label for="no_hp">No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="item_id">Barang yang Diminta (Stok Tersedia)</label>
                            <select class="form-control" id="item_id" name="item_id" required>
                                <option value="">Pilih Barang</option>
                                @foreach($items as $id => $nama_barang)
                                    <option value="{{ $id }}" {{ old('item_id') == $id ? 'selected' : '' }}>{{ $nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_request">Jumlah Permintaan</label>
                            <input type="number" class="form-control" id="jumlah_request" name="jumlah_request" min="1" value="{{ old('jumlah_request') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Ajukan Permintaan Barang</button>
                        <a href="{{ route('landing-page') }}" class="btn btn-secondary mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection