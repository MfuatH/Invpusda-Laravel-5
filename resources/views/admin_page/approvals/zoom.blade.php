@extends('layouts.app')

@section('title', 'Approval Request Link Zoom')

@section('content')
<div class="container-fluid px-4">

    {{-- Header Judul --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-video text-primary mr-2"></i> Approval Request Link Zoom
        </h4>
    </div>

    {{-- Card Table --}}
    <div class="card shadow-sm w-100">
        <div class="card-body">
            @if ($requests->isEmpty())
                <p class="text-center text-muted mb-0">
                    Tidak ada permintaan link Zoom yang menunggu persetujuan saat ini.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>PEMOHON</th>
                                <th>NIP</th>
                                <th>NO HP</th>
                                <th>BIDANG</th>
                                <th>NAMA RAPAT</th>
                                <th>JADWAL</th>
                                <th>STATUS</th>
                                <th style="width: 180px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $r)
                            <tr>
                                <td>{{ $r->nama_pemohon }}</td>
                                <td>{{ $r->nip ?? '-' }}</td>
                                <td>{{ $r->no_hp ?? '-' }}</td>
                                <td>{{ $r->bidang->nama ?? '-' }}</td>
                                <td>
                                    <strong>{{ $r->nama_rapat }}</strong>
                                    @if($r->keterangan)
                                        <br><small class="text-muted">{{ $r->keterangan }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        Mulai: {{ \Carbon\Carbon::parse($r->jadwal_mulai)->format('d/m/Y H:i') }}<br>
                                        @if($r->jadwal_selesai)
                                            Selesai: {{ \Carbon\Carbon::parse($r->jadwal_selesai)->format('d/m/Y H:i') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($r->status === 'approved') 
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($r->status === 'rejected') 
                                        <span class="badge badge-danger">Rejected</span>
                                    @else 
                                        <span class="badge badge-warning text-dark">Pending</span> 
                                    @endif
                                </td>
                                <td>
                                    {{-- LOGIKA TOMBOL AKSI --}}
                                    @if($r->status === 'pending')
                                        <div class="d-flex justify-content-center">
                                            {{-- Tombol TERIMA (Tanpa Icon) --}}
                                            <button class="btn btn-sm btn-success mr-1 approve-btn"
                                                data-toggle="modal" data-target="#approveModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemohon }}"
                                                data-meeting="{{ $r->nama_rapat }}"
                                                data-time="{{ \Carbon\Carbon::parse($r->jadwal_mulai)->format('d/m/Y H:i') }}">
                                                Terima
                                            </button>
                                            
                                            {{-- Tombol TOLAK (Tanpa Icon) --}}
                                            <button class="btn btn-sm btn-danger reject-btn"
                                                data-toggle="modal" data-target="#rejectModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemohon }}"
                                                data-meeting="{{ $r->nama_rapat }}">
                                                Tolak
                                            </button>
                                        </div>
                                    @elseif($r->status === 'approved')
                                        @if($r->link_zoom)
                                            <a href="{{ $r->link_zoom }}" target="_blank" class="btn btn-sm btn-info text-white shadow-sm">
                                                Buka Link
                                            </a>
                                        @else
                                            <span class="small text-muted">-</span>
                                        @endif
                                    @else
                                        <span class="small text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($requests->hasPages())
                <div class="mt-3 d-flex justify-content-end">
                    {{ $requests->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL APPROVE (ADA INPUT LINK ZOOM) --}}
{{-- =================================================================== --}}
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">
                        <i class="fas fa-check-circle mr-1"></i> Konfirmasi Persetujuan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menyetujui permintaan Link Zoom berikut:</p>
                    
                    <div class="alert alert-light border shadow-sm mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Rapat:</strong> <span id="approve-meeting-name"></span><br>
                                <strong>Waktu:</strong> <span id="approve-meeting-time"></span>
                            </div>
                            <div class="col-md-4">
                                <strong>Pemohon:</strong> <br>
                                <span id="approve-requester-name"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Masukkan Link Zoom Meeting <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="url" name="link_zoom" class="form-control" required placeholder="https://zoom.us/j/..." autocomplete="off">
                        </div>
                        <small class="text-muted">Link ini akan dikirim via WA dan ditampilkan di tabel.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button class="btn btn-success btn-sm" type="submit">
                        Kirim & Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL REJECT --}}
{{-- =================================================================== --}}
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Konfirmasi Penolakan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak permintaan berikut:</p>
                    <div class="alert alert-warning">
                        <strong>Rapat:</strong> <span id="reject-meeting-name"></span><br>
                        <strong>Pemohon:</strong> <span id="reject-requester-name"></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="note" class="form-control" rows="3" required placeholder="Contoh: Jadwal bentrok, ruangan penuh, dll..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger btn-sm" type="submit">
                        Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
    
    const approveTemplate = '{{ route("zoom.requests.approve", ["reqZoom" => "PLACEHOLDER"]) }}';
    const rejectTemplate = '{{ route("zoom.requests.reject", ["reqZoom" => "PLACEHOLDER"]) }}';

    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        $('#approveForm').attr('action', approveTemplate.replace('PLACEHOLDER', id));
        
        $('#approve-meeting-name').text($(this).data('meeting'));
        $('#approve-meeting-time').text($(this).data('time'));
        $('#approve-requester-name').text($(this).data('name'));
    });

    $('.reject-btn').on('click', function(){
        var id = $(this).data('id');
        $('#rejectForm').attr('action', rejectTemplate.replace('PLACEHOLDER', id));
        
        $('#reject-meeting-name').text($(this).data('meeting'));
        $('#reject-requester-name').text($(this).data('name'));
    });
});
</script>
@endpush

@push('styles')
<style>
    .table { font-size: 14px; background: #fff; }
    .table thead th { background-color: #343a40; color: #fff; border-color: #454d55; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: #f8f9fa; }
    /* Atur lebar tombol agar terlihat rapi */
    .btn-sm { padding: 0.25rem 0.75rem; font-size: 0.875rem; border-radius: 0.2rem; }
    .modal-content { border-radius: 8px; border: none; }
</style>
@endpush