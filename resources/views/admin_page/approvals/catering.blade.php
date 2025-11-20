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
                                            {{-- Tombol Approve --}}
                                            <button class="btn btn-sm btn-success mr-2 approve-btn"
                                                data-toggle="modal" data-target="#approveModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemesan }}"
                                                data-keperluan="{{ $r->keperluan }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($r->tanggal_kegiatan)->format('d-m-Y H:i') }}"
                                                data-tempat="{{ $r->tempat }}"
                                                data-peserta="{{ $r->jumlah_peserta }}"
                                                data-konsumsi="{{ $r->jenis_konsumsi_string }}" 
                                                data-nota_url="{{ $r->nota_dinas_url }}"
                                                data-keterangan="{{ $r->keterangan ?? '-' }}">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            
                                            {{-- Tombol Hapus --}}
                                            <button class="btn btn-sm btn-danger delete-btn"
                                                data-toggle="modal" data-target="#deleteModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemesan }}"
                                                data-keperluan="{{ $r->keperluan }}">
                                                <i class="fas fa-trash"></i> Hapus
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

{{-- =================================================================== --}}
{{-- MODAL APPROVE (TETAP SEPERTI YANG SUDAH DISETUJUI) --}}
{{-- =================================================================== --}}
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content">
            <form id="approveForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="approveModalLabel">
                        <i class="fas fa-check-circle text-success mr-2"></i> Konfirmasi Persetujuan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Anda akan menyetujui pemesanan makanan berikut:</p>
                    
                    <div class="alert alert-light border shadow-sm">
                        <div class="row">
                            {{-- Kolom Kiri --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted d-block">PEMESAN</small>
                                    <i class="fas fa-user text-primary mr-2"></i>
                                    <span id="approve-name" class="font-weight-bold"></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">KEPERLUAN</small>
                                    <i class="fas fa-clipboard-list text-primary mr-2"></i>
                                    <span id="approve-keperluan" class="font-weight-bold"></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">TANGGAL KEGIATAN</small>
                                    <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                    <span id="approve-tanggal" class="font-weight-bold"></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">TEMPAT</small>
                                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                    <span id="approve-tempat" class="font-weight-bold"></span>
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <small class="text-muted d-block">JUMLAH PESERTA</small>
                                    <i class="fas fa-users text-primary mr-2"></i>
                                    <span id="approve-peserta" class="font-weight-bold"></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">JENIS KONSUMSI</small>
                                    <i class="fas fa-utensils text-primary mr-2"></i>
                                    <span id="approve-konsumsi" class="font-weight-bold"></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">KETERANGAN</small>
                                    <i class="fas fa-info-circle text-primary mr-2"></i>
                                    <span id="approve-keterangan" class="font-weight-bold"></span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">NOTA DINAS</small>
                                    <button type="button" id="approve-nota-btn" class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="fas fa-eye mr-1"></i> Lihat File Nota
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fas fa-check mr-1"></i> Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL HAPUS --}}
{{-- =================================================================== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Penghapusan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                    <h4>Apakah Anda Yakin?</h4>
                    <p class="text-muted">
                        Anda akan menghapus data pemesanan dari <strong><span id="delete-name"></span></strong> 
                        untuk keperluan <strong><span id="delete-keperluan"></span></strong>.
                        <br>
                        <small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger btn-sm" type="submit">Ya, Hapus Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL PREVIEW FILE (DIKEMBALIKAN KE UKURAN AWAL) --}}
{{-- =================================================================== --}}
{{-- PERUBAHAN: Kembali ke modal-lg dan height 75vh --}}
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
                {{-- Konten akan diisi JavaScript, style width/height 100% agar pas di wadah --}}
                <div id="previewContent" style="width: 100%; height: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
    
    const approveTemplate = '{{ route("catering.approve", ["catering" => "PLACEHOLDER"]) }}';
    const deleteTemplate = '{{ route("catering.destroy", ["catering" => "PLACEHOLDER"]) }}';
    
    function getFinalUrl(template, id) {
        return template.replace('PLACEHOLDER', id);
    }

    // 1. Tombol APPROVE
    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        var notaUrl = $(this).data('nota_url'); 
        
        $('#approveForm').attr('action', getFinalUrl(approveTemplate, id));
        
        $('#approve-name').text($(this).data('name'));
        $('#approve-keperluan').text($(this).data('keperluan'));
        $('#approve-tanggal').text($(this).data('tanggal'));
        $('#approve-tempat').text($(this).data('tempat'));
        $('#approve-peserta').text($(this).data('peserta'));
        $('#approve-konsumsi').text($(this).data('konsumsi'));
        $('#approve-keterangan').text($(this).data('keterangan'));
        
        $('#approve-nota-btn').data('url', notaUrl); 
    });

    // 2. Tombol DELETE
    $('.delete-btn').on('click', function(){
        var id = $(this).data('id');
        $('#deleteForm').attr('action', getFinalUrl(deleteTemplate, id));
        
        $('#delete-name').text($(this).data('name'));
        $('#delete-keperluan').text($(this).data('keperluan'));
    });

    // 3. Tombol LIHAT FILE
    $(document).on('click', '#approve-nota-btn', function() {
        var fileUrl = $(this).data('url');
        var previewContent = $('#previewContent');
        
        previewContent.empty(); 

        if (fileUrl && fileUrl !== '#') {
            if (fileUrl.match(/\.(jpeg|jpg|gif|png)$/i) != null) {
                previewContent.html('<img src="' + fileUrl + '" style="width: 100%; height: 100%; object-fit: contain;">');
            } else {
                // Style iframe di-set 100% agar mengisi modal-body
                previewContent.html('<iframe src="' + fileUrl + '" style="width: 100%; height: 100%; border: none;" frameborder="0"></iframe>');
            }
            $('#filePreviewModal').modal('show');
        } else {
            alert('File nota dinas tidak ditemukan atau rusak.');
        }
    });

    // 4. Fix Scroll
    $('#filePreviewModal').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });

});
</script>
@endpush

@push('styles')
<style>
.table { font-size: 14px; background: #fff; }
.table thead th { background-color: #1f2937; color: #fff; font-weight: 600; text-align: center; }
.table tbody tr td { background-color: #ffffff; color: #333; vertical-align: middle; }
.table-striped tbody tr:nth-of-type(odd) { background-color: #f8f9fa; }
.btn-sm { font-size: 13px; padding: 5px 10px; }
.text-dark { color: #444 !important; }
.card { border: 1px solid #ddd; border-radius: 10px; }
.card-body { padding: 20px; }
h4 { color: #1f2937; }

.alert-light {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}
.alert-light small {
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
    margin-bottom: 4px;
}
.alert-light span {
    font-size: 1rem;
    color: #333;
}
</style>
@endpush