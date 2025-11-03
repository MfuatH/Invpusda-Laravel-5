@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Pengguna Baru</h1>

    {{-- Cek Hak Akses --}}
    @if (Auth::user()->role !== 'super_admin')
        <div class="alert alert-danger">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pengguna</h6>
                <a href="{{ route('super.users.index') }}" class="btn btn-secondary btn-sm">
                    ← Kembali
                </a>
            </div>

            <div class="card-body">
                {{-- Notifikasi Sukses --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Notifikasi Error --}}
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

                <form action="{{ route('super.users.store') }}" method="POST" class="needs-validation" novalidate>
                    {{ csrf_field() }} <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                value="{{ old('name') }}" required>
                            <div class="invalid-feedback">Nama wajib diisi.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" 
                                value="{{ old('email') }}" required>
                            <div class="invalid-feedback">Email wajib diisi.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" 
                                **value="{{ old('no_hp') }}"** placeholder="Masukkan nomor HP">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <div class="invalid-feedback">Kata sandi wajib diisi.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <div class="invalid-feedback">Konfirmasi kata sandi wajib diisi.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-semibold">Nama Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled selected>Pilih role</option>
                                <option value="admin_barang" {{ old('role') == 'admin_barang' ? 'selected' : '' }}>Admin Barang</option>
                            </select>
                            <div class="invalid-feedback">Role wajib dipilih.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bidang_id" class="form-label fw-semibold">Bidang</label>
                            <select name="bidang_id" id="bidang_id" class="form-select" required>
                                <option value="" disabled selected>Pilih bidang</option>
                                @foreach ($bidangs as $bidang)
                                    <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Bidang wajib dipilih.</div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

{{-- Script validasi sederhana Bootstrap --}}
<script>
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection