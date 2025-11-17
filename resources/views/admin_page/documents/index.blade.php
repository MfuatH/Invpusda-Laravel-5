@extends('layouts.app')

@section('title', 'Manajemen Dokumen')

@section('content')
<div class="container-fluid px-4">

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if(session('error')) {{-- Ditambahkan untuk notif error --}}
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> Gagal memproses permintaan.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-file-alt text-primary mr-2"></i> Manajemen Dokumen
        </h4>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm w-100">
        <div class="card-body">

            {{-- Pencarian (Tombol Export/Upload Dihapus) --}}
            <div class="d-flex justify-content-end align-items-center mb-3 flex-wrap">
                <form action="{{ route('documents.index') }}" method="GET" class="form-inline ml-auto">
                    <input type="text" name="search" class="form-control form-control-sm mr-2"
                           placeholder="Cari dokumen..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                </form>
            </div>

            {{-- Tabel Dokumen --}}
            @if(isset($documents) && $documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle"> {{-- Style Diperbarui --}}
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th style="width: 15%;">Pengunggah</th>
                            <th style="width: 10%;">NIP</th>
                            <th style="width: 25%;">Nama File</th>
                            <th style="width: 25%;">Keterangan</th>
                            <th style="width: 10%;">Tgl. Upload</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                        <tr>
                            <td>{{ $doc->pengunggah }}</td>
                            <td class="text-center">{{ $doc->nip ?? '-' }}</td>
                            
                            {{-- PERBAIKAN NAMA FILE --}}
                            <td>{{ $doc->file_original_name }}</td>
                            
                            <td>{{ \Illuminate\Support\Str::limit($doc->keterangan, 80) }}</td>
                            <td class="text-center">{{ $doc->created_at->format('d-m-Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 10px;">
                                    
                                    {{-- Tombol Download --}}
                                    <a href="{{ route('documents.download', $doc->id) }}" 
                                       class="btn btn-link text-success p-0 btn-sm" title="Download">
                                        Download
                                    </a>
                                    
                                    {{-- Tombol Lihat (Preview) --}}
                                    <button type="button" 
                                            class="btn btn-link text-primary p-0 btn-sm btn-view-doc"
                                            data-url="{{ $doc->file_url }}" 
                                            data-toggle="modal" 
                                            data-target="#modalViewDokumen" title="Lihat">
                                        Lihat
                                    </button>
                                    
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('documents.destroy', $doc->id) }}" 
                                          method="POST" class="d-inline m-0 p-0">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" 
                                                class="btn btn-link text-danger p-0 btn-sm" 
                                                onclick="return confirm('Yakin ingin menghapus dokumen ini?')" title="Hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                <small class="text-muted">
                    Menampilkan {{ $documents->firstItem() }} - {{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen
                </small>
                <div>
                    {{ $documents->links() }}
                </div>
            </div>
            @else
            <p class="text-center text-muted mb-0">Belum ada data dokumen yang di-upload oleh user.</p>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modalViewDokumen" tabindex="-1" role="dialog" aria-labelledby="modalViewDokumenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalViewDokumenLabel">Preview Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 75vh;">
                <div id="previewContent" style="height: 100%;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function() {
    
    // Script untuk MODAL LIHAT DOKUMEN
    $('.btn-view-doc').on('click', function() {
        var fileUrl = $(this).data('url');
        var previewContent = $('#previewContent');
        
        previewContent.empty(); 

        if (fileUrl && fileUrl !== '#') {
            if (fileUrl.match(/\.(jpeg|jpg|gif|png)$/i) != null) {
                previewContent.html('<img src="' + fileUrl + '" style="width: 100%; height: auto;">');
            } else {
                previewContent.html('<iframe src="' + fileUrl + '" style="width: 100%; height: 100%; border: none;"></iframe>');
            }
        } else {
            previewContent.html('<p class="text-center text-muted">File tidak ditemukan.</p>');
        }
    });

});
</script>
@endpush

@push('styles')
<style>
/* Style tabel (diambil dari Manajemen Barang) */
.table {
    font-size: 14px;
    background: #fff;
    vertical-align: middle; /* Menambahkan ini agar teks di tengah */
}
.table thead th {
    background-color: #1f2937;
    color: #fff;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}
.table tbody tr td {
    background-color: #ffffff;
    color: #333;
    vertical-align: middle;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f8f9fa;
}
.btn-sm {
    font-size: 13px;
    padding: 0; 
}
.card {
    border: 1px solid #ddd;
    border-radius: 10px;
}
.card-body {
    padding: 20px;
}
h4 {
    color: #1f2937;
}
</style>
@endpush