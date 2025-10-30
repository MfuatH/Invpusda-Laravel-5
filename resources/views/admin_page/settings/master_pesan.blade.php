@extends('layouts.app')

@section('title', 'Master Pesan')

@section('content')
<div class="container-fluid px-4">

    {{-- Judul Halaman --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-envelope text-primary mr-2"></i> Master Pesan
        </h4>
    </div>

    {{-- Card Panduan --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body bg-light" style="border-left: 5px solid #2563eb;">
            <h6 class="font-weight-bold mb-3">
                💡 Cara Menggunakan Template
            </h6>
            <p class="mb-2">Gunakan placeholder di bawah ini di dalam pesan Anda. Sistem akan otomatis menggantinya dengan data sesuai saat request disetujui.</p>
            <ul class="list-unstyled mb-0 text-secondary">
                <li><code>@nama</code> : Nama pemohon.</li>
                <li><code>@kegiatan</code> : Nama kegiatan/keterangan.</li>
                <li><code>@tanggal</code> : Tanggal pelaksanaan.</li>
                <li><code>@link</code> : Link Zoom yang disetujui.</li>
            </ul>
        </div>
    </div>

    {{-- Card Form Master Pesan --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="font-weight-bold text-primary mb-3">Kelola Master Pesan</h6>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action= method="POST">
                {{ csrf_field() }}

                {{-- Pilih Bidang --}}
                <div class="form-group">
                    <label class="font-weight-bold">Pilih Bidang</label>
                    <select name="bidang_id" class="form-control" required>
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Textarea Template --}}
                <div class="form-group">
                    <label class="font-weight-bold">Master Pesan</label>
                    <textarea name="pesan_template" rows="4" class="form-control" placeholder="Contoh: Halo @nama, permintaan link Zoom untuk kegiatan '@kegiatan' pada tanggal @tanggal telah disetujui. Berikut linknya: @link. Terima kasih." required>{{ old('pesan_template') }}</textarea>
                </div>

                {{-- Tombol Simpan --}}
                <div class="text-right">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Master Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
h4 {
    color: #1f2937;
}
.card {
    border-radius: 10px;
}
.card-body {
    background-color: #ffffff;
    padding: 20px;
}
.bg-light {
    background-color: #f8faff !important;
}
ul li code {
    background-color: #e9ecef;
    padding: 3px 6px;
    border-radius: 4px;
}
.btn-primary {
    background-color: #6366f1;
    border-color: #6366f1;
}
.btn-primary:hover {
    background-color: #4f46e5;
    border-color: #4f46e5;
}
</style>
@endpush
