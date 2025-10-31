@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <h1 class="h3 mb-4 text-gray-800">Edit Pengguna</h1>

    @if (Auth::user()->role !== 'super_admin')
        <div class="alert alert-danger">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </div>
    @else
        <!-- Card Utama -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Pengguna</h6>
                <a href="{{ route('super.users.index') }}" class="btn btn-secondary btn-sm">
                    ← Kembali
                </a>
            </div>

            <div class="card-body">
                <!-- Notifikasi -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi Kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Edit User -->
                <form action="{{ route('super.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nama -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <!-- Nomor HP (Baru Ditambahkan) -->
                        <div class="col-md-6 mb-3">
                            <label for="nomor_hp" class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" name="nomor_hp" id="nomor_hp" class="form-control" 
                                   value="{{ old('nomor_hp', $user->nomor_hp) }}" placeholder="Masukkan nomor HP">
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Kosongkan jika tidak mengubah">
                        </div>

                        <!-- Role -->
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-semibold">Nama Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled>Pilih role</option>
                                <option value="admin barang" {{ old('role', $user->role) == 'admin barang' ? 'selected' : '' }}>Admin Barang</option>
                            </select>
                        </div>

                        <!-- Bidang -->
                        <div class="col-md-6 mb-3">
                            <label for="bidang_id" class="form-label fw-semibold">Bidang</label>
                            <select name="bidang_id" id="bidang_id" class="form-select" required>
                                <option value="" disabled>Pilih bidang</option>
                                @foreach ($bidangs as $bidang)
                                    <option value="{{ $bidang->id }}" {{ old('bidang_id', $user->bidang_id) == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            Perbarui Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
