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
            <h6 class="font-weight-bold mb-3">💡 Cara Menggunakan Template</h6>
            <p class="mb-2">
                Gunakan placeholder di bawah ini di dalam pesan Anda.
                Sistem akan otomatis menggantinya dengan data sesuai saat request disetujui.
            </p>
            <ul class="list-unstyled mb-0 text-secondary">
                <li><code>@nama</code> : Nama pemohon.</li>
                <li><code>@kegiatan</code> : Nama kegiatan/keterangan.</li>
                <li><code>@tanggal</code> : Tanggal pelaksanaan.</li>
                <li><code>@link</code> : Link Zoom yang disetujui.</li>
            </ul>
        </div>
    </div>

    {{-- Card Form Master Pesan --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="font-weight-bold text-primary mb-3">Tambah / Ubah Template Pesan</h6>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('template.update') }}" method="POST">
                {{ csrf_field() }}

                {{-- Pilih Bidang --}}
                <div class="form-group">
                    <label class="font-weight-bold">Pilih Bidang</label>
                    <select name="bidang_id" class="form-control" required
                        {{ Auth::user()->role != 'super_admin' ? 'disabled' : '' }}>
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}"
                                {{ (Auth::user()->bidang_id == $bidang->id) ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>

                    @if(Auth::user()->role != 'super_admin')
                        {{-- Hidden input agar tetap terkirim --}}
                        <input type="hidden" name="bidang_id" value="{{ Auth::user()->bidang_id }}">
                    @endif
                </div>

                {{-- Textarea Template --}}
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Isi Template Pesan</label>
                    <textarea name="pesan_template" rows="4" class="form-control" required
                        placeholder="Contoh: Halo @nama, permintaan link Zoom untuk kegiatan '@kegiatan' pada tanggal @tanggal telah disetujui. Berikut linknya: @link. Terima kasih.">{{ old('pesan_template') }}</textarea>
                </div>

                {{-- Tombol Simpan --}}
                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Master Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Template Pesan --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="font-weight-bold text-primary mb-3">Daftar Template Pesan</h6>

            @if($templates->isEmpty())
                <p class="text-muted">Belum ada template pesan yang tersimpan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Bidang</th>
                                <th>Isi Template</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $index => $template)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $template->nama ?? '-' }}</td>
                                    <td>{{ $template->pesan_template }}</td>
                                    <td class="text-center">
                                        @if(Auth::user()->role == 'super_admin')
                                            <form action="{{ route('template.update') }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus template ini?')">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="bidang_id" value="{{ $template->bidang_id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
h4 { color: #1f2937; }
.card { border-radius: 10px; }
.bg-light { background-color: #f8faff !important; }
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
.table td, .table th { vertical-align: middle; }
</style>
@endpush
