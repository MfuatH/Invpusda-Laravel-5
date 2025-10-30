@extends('layouts.app')

@section('title', 'Approval Request Link Zoom')

@section('content')
<div class="container-fluid px-4">

    {{-- Judul di luar card --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-video text-primary mr-2"></i> Approval Request Link Zoom
        </h4>
    </div>

    {{-- Card daftar permintaan --}}
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
                                <th>AKSI</th>
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
                                    {{ $r->nama_rapat }}
                                    @if($r->keterangan)
                                        <small class="d-block text-muted">{{ $r->keterangan }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        Mulai: <strong>{{ \Carbon\Carbon::parse($r->jadwal_mulai)->format('d/m/Y H:i') }}</strong>
                                        @if($r->jadwal_selesai)
                                            <br>Selesai: <strong>{{ \Carbon\Carbon::parse($r->jadwal_selesai)->format('d/m/Y H:i') }}</strong>
                                        @endif
                                    </div>
                                </td>
                                <td><span class="text-dark font-weight-bold">{{ ucfirst($r->status) }}</span></td>
                                <td>
                                    @if($r->status === 'pending')
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-success mr-2 approve-btn"
                                                data-toggle="modal" data-target="#approveModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemohon }}"
                                                data-meeting="{{ $r->nama_rapat }}"
                                                data-time="{{ \Carbon\Carbon::parse($r->jadwal_mulai)->format('d/m/Y H:i') }}">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                            <button class="btn btn-sm btn-danger reject-btn"
                                                data-toggle="modal" data-target="#rejectModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemohon }}"
                                                data-meeting="{{ $r->nama_rapat }}">
                                                <i class="fas fa-times mr-1"></i> Tolak
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

                {{-- Pagination --}}
                @if($requests->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $requests->firstItem() }} sampai {{ $requests->lastItem() }} 
                        dari {{ $requests->total() }} permintaan
                    </div>
                    {{ $requests->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Konfirmasi Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menyetujui permintaan Link Zoom berikut:</p>
                    <div class="alert alert-info">
                        <strong id="approve-meeting-name"></strong><br>
                        Waktu: <span id="approve-meeting-time"></span><br>
                        Pemohon: <span id="approve-requester-name"></span>
                    </div>
                    <div class="form-group">
                        <label>Link Zoom Meeting <span class="text-danger">*</span></label>
                        <input type="text" name="link_zoom" class="form-control" required placeholder="https://zoom.us/j/...">
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-success" type="submit"><i class="fas fa-check mr-1"></i> Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak permintaan Link Zoom berikut:</p>
                    <div class="alert alert-warning">
                        <strong id="reject-meeting-name"></strong><br>
                        Pemohon: <span id="reject-requester-name"></span>
                    </div>
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="note" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="submit"><i class="fas fa-times mr-1"></i> Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        $('#approveForm').attr('action', '/dashboard/approvals/zoom/' + id + '/approve');
        $('#approve-meeting-name').text($(this).data('meeting'));
        $('#approve-meeting-time').text($(this).data('time'));
        $('#approve-requester-name').text($(this).data('name'));
    });

    $('.reject-btn').on('click', function(){
        var id = $(this).data('id');
        $('#rejectForm').attr('action', '/dashboard/approvals/zoom/' + id + '/reject');
        $('#reject-meeting-name').text($(this).data('meeting'));
        $('#reject-requester-name').text($(this).data('name'));
    });
});
</script>
@endpush

@push('styles')
<style>
.table {
    font-size: 14px;
    background: #fff;
}
.table thead th {
    background-color: #1f2937; /* Header gelap */
    color: #fff;
    font-weight: 600;
    text-align: center;
}
.table tbody tr td {
    background-color: #ffffff; /* Isi putih */
    color: #333; /* Teks isi abu gelap */
    vertical-align: middle;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f8f9fa; /* Baris abu muda */
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
    color: #444 !important; /* Warna status abu gelap */
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
