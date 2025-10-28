@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-video"></i> Form Permintaan Link Zoom
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

                    <form action="{{ route('request.zoom.store') }}" method="POST">
                        
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
                            <label for="nama_rapat">Nama Rapat/Kegiatan</label>
                            <input type="text" class="form-control" id="nama_rapat" name="nama_rapat" value="{{ old('nama_rapat') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="jadwal_mulai">Tanggal & Waktu Mulai</label>
                            <input type="datetime-local" class="form-control" id="jadwal_mulai" name="jadwal_mulai" value="{{ old('jadwal_mulai') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="jadwal_selesai">Tanggal & Waktu Selesai (Opsional)</label>
                            <input type="datetime-local" class="form-control" id="jadwal_selesai" name="jadwal_selesai" value="{{ old('jadwal_selesai') }}">
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Ajukan Permintaan Zoom</button>
                        <a href="{{ route('landing-page') }}" class="btn btn-secondary mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
