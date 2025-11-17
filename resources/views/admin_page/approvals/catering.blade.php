@extends('layouts.app')

@section('title', 'Approval Pemesanan Makanan')

@section('content')
<div class="container-fluid px-4">

    {{-- Header Judul --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-utensils text-primary mr-2"></i>
            Approval Pemesanan Makanan
        </h4>
    </div>

    {{-- Card Tabel --}}
    <div class="card shadow-sm w-100">
        <div class="card-body">
            
            @if ($caterings->isEmpty())
                <p class="text-center text-muted mb-0">Tidak ada pemesanan makanan yang menunggu persetujuan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>PEMESAN</th>
                                <th>NIP</th>
                                <th>KEPERLUAN</th>
                                <th>TANGGAL KEGIATAN</th>
                                <th>JML PESERTA</th>
                                <th>TGL KIRIM</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($caterings as $r)
                            <tr>
                                <td>{{ $r->nama_pemesan }}</td>
                                <td>{{ $r->nip ?? '-' }}</td>
                                <td>{{ $r->keperluan }}</td>
                                <td>{{ \Carbon\Carbon::parse($r->tanggal_kegiatan)->format('d-m-Y H:i') }}</td>
                                <td>{{ $r->jumlah_peserta }}</td>
                                <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="text-dark font-weight-bold">{{ ucfirst($r->status) }}</span>
                                </td>
                                <td>
                                    @if($r->status === 'pending')
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-success mr-2 approve-btn"
                                                data-toggle="modal" data-target="#approveModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemesan }}"
                                                data-keperluan="{{ $r->keperluan }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($r->tanggal_kegiatan)->format('d-m-Y H:i') }}"
                                                data-tempat="{{ $r->tempat }}"
                                                data-peserta="{{ $r->jumlah_peserta }}"
                                                data-konsumsi="{{ $r->jenis_konsumsi_string }}" 
                                                data-nota_url="{{ $r->nota_dinas_url }}" {{-- Ini adalah data URL file --}}
                                                data-keterangan="{{ $r->keterangan ?? '-' }}">
                                                Approve
                                            </button>
                                            
                                            <button class="btn btn-sm btn-danger reject-btn"
                                                data-toggle="modal" data-target="#rejectModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemesan }}"
                                                data-keperluan="{{ $r->keperluan }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($r->tanggal_kegiatan)->format('d-m-Y H:i') }}">
                                                Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-secondary">-</span>
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

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Konfirmasi Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menyetujui pemesanan makanan berikut:</p>
                    
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Pemesan:</strong> <span id="approve-name"></span></p>
                                <p class="mb-1"><strong>Keperluan:</strong> <span id="approve-keperluan"></span></p>
                                <p class="mb-1"><strong>Tanggal Kegiatan:</strong> <span id="approve-tanggal"></span></p>
                                <p class="mb-0"><strong>Tempat:</strong> <span id="approve-tempat"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Jumlah Peserta:</strong> <span id="approve-peserta"></span></p>
                                <p class="mb-1"><strong>Jenis Konsumsi:</strong> <span id="approve-konsumsi"></span></p>
                                <p class="mb-1"><strong>Keterangan:</strong> <span id="approve-keterangan"></span></p>
                                <p class="mb-0"><strong>Nota Dinas:</strong> 
                                    
                                    {{-- ============ PERUBAHAN TOMBOL DI SINI ============ --}}
                                    {{-- Diubah dari <a> menjadi <button> --}}
                                    <button type="button" id="approve-nota-btn" class="btn btn-sm btn-primary">
                                        Lihat File
                                    </button>
                                    {{-- ================================================ --}}

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="submit">Setujui Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    {{-- ... (Isi modal reject Anda, tidak perlu diubah) ... --}}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak pemesanan makanan berikut:</p>
                    <div class="alert alert-warning">
                        <strong id="reject-keperluan"></strong><br>
                        Pemesan: <span id="reject-name"></span><br>
                        Tanggal Kegiatan: <span id="reject-tanggal"></span>
                    </div>
                    <div class="form-group">
                        <label>Alasan Penolakan</label>
                        <textarea name="note" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="submit">Tolak Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewModalLabel">Preview Nota Dinas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 75vh;">
                {{-- Konten akan diisi oleh JavaScript --}}
                <div id="previewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
    
    const approveTemplate = '{{ route("catering.approve", ["catering" => "PLACEHOLDER"]) }}';
    const rejectTemplate = '{{ route("catering.reject", ["catering" => "PLACEHOLDER"]) }}';
    
    function getFinalUrl(template, id) {
        return template.replace('PLACEHOLDER', id);
    }

    // 1. Saat tombol APPROVE (hijau) di tabel diklik
    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        var notaUrl = $(this).data('nota_url'); // Ambil URL file
        
        $('#approveForm').attr('action', getFinalUrl(approveTemplate, id));
        
        // Mengisi data modal approve
        $('#approve-name').text($(this).data('name'));
        $('#approve-keperluan').text($(this).data('keperluan'));
        $('#approve-tanggal').text($(this).data('tanggal'));
        $('#approve-tempat').text($(this).data('tempat'));
        $('#approve-peserta').text($(this).data('peserta'));
        $('#approve-konsumsi').text($(this).data('konsumsi'));
        $('#approve-keterangan').text($(this).data('keterangan'));
        
        // ============ PERUBAHAN SCRIPT DI SINI ============
        // Simpan URL file ke tombol "Lihat File"
        $('#approve-nota-btn').data('url', notaUrl); 
        // =================================================
    });

    // 2. Saat tombol REJECT (merah) di tabel diklik
    $('.reject-btn').on('click', function(){
        var id = $(this).data('id');
        $('#rejectForm').attr('action', getFinalUrl(rejectTemplate, id));
        
        $('#reject-keperluan').text($(this).data('keperluan'));
        $('#reject-name').text($(this).data('name'));
        $('#reject-tanggal').text($(this).data('tanggal'));
    });


    // ==========================================================
    // --- SCRIPT BARU UNTUK MENANGANI TOMBOL "LIHAT FILE" ---
    // ==========================================================

    // 3. Saat tombol "Lihat File" (#approve-nota-btn) di dalam modal diklik
    $(document).on('click', '#approve-nota-btn', function() {
        var fileUrl = $(this).data('url');
        var previewContent = $('#previewContent');
        
        // Kosongkan konten preview sebelumnya
        previewContent.empty(); 

        if (fileUrl && fileUrl !== '#') {
            // Cek apakah file adalah gambar
            if (fileUrl.match(/\.(jpeg|jpg|gif|png)$/i) != null) {
                // Jika gambar, gunakan tag <img>
                previewContent.html('<img src="' + fileUrl + '" style="width: 100%; height: auto;">');
            } else {
                // Jika bukan gambar (PDF, dll), gunakan <iframe>
                previewContent.html('<iframe src="' + fileUrl + '" width="100%" height="100%" frameborder="0"></iframe>');
            }
            
            // Buka modal preview (#filePreviewModal)
            $('#filePreviewModal').modal('show');
        } else {
            alert('File nota dinas tidak ditemukan atau rusak.');
        }
    });

    // 4. (PENTING) Mengelola tumpukan modal
    // Saat modal preview (#filePreviewModal) ditutup...
    $('#filePreviewModal').on('hidden.bs.modal', function () {
        // Bootstrap secara otomatis menghapus 'modal-open' dari body.
        // Kita harus menambahkannya kembali agar modal approval (#approveModal)
        // tetap bisa di-scroll.
        $('body').addClass('modal-open');
    });

});
</script>
@endpush

@push('styles')
{{-- Style Anda sudah benar, tidak perlu diubah --}}
<style>
.table {
    font-size: 14px;
    background: #fff;
}
.table thead th {
    background-color: #1f2937;
    color: #fff;
    font-weight: 600;
    text-align: center;
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
    padding: 5px 10px;
}
.alert-info, .alert-warning {
    font-size: 14px;
    margin-bottom: 10px;
}
.text-dark {
    color: #444 !important;
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
.alert-info p {
    margin-bottom: 0.5rem;
}

/* Style tambahan untuk modal preview (opsional) */
#previewContent iframe {
    width: 100%;
    height: 70vh; /* Pastikan iframe tinggi */
    border: none;
}
</style>
@endpush