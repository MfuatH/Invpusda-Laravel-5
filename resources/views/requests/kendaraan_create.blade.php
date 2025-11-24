@extends('layouts.app')

@section('title', 'Peminjaman Kendaraan Dinas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Form Peminjaman Kendaraan Dinas</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif

                    <form action="{{ route('request.kendaraan.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Urgensi</label>
                            <input type="text" name="urgensi" class="form-control" value="{{ old('urgensi') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal & Jam Ambil</label>
                            <input type="datetime-local" name="tanggal_ambil" class="form-control" value="{{ old('tanggal_ambil') ? \Carbon\Carbon::parse(old('tanggal_ambil'))->format('Y-m-d\\TH:i') : '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal & Jam Kembali</label>
                            <input type="datetime-local" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali') ? \Carbon\Carbon::parse(old('tanggal_kembali'))->format('Y-m-d\\TH:i') : '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Plat No Kendaraan</label>
                            <input type="text" name="plat_no" class="form-control" value="{{ old('plat_no') }}">
                        </div>

                        <div class="text-end">
                            <a href="{{ route('landing-page') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
