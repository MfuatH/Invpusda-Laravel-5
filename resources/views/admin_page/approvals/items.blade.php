@extends('layouts.app')

@section('title', 'Approval Request Barang')

@section('content')
<div class="container-fluid px-4">

    {{-- Header Judul --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-check-circle text-primary mr-2"></i> Approval Request Barang
        </h4>
    </div>

    {{-- Card Tabel --}}
    <div class="card shadow-sm w-100">
        <div class="card-body">
            @if ($requests->isEmpty())
                <p class="text-center text-muted mb-0">Tidak ada permintaan barang yang menunggu persetujuan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>PEMINTA</th>
                                <th>NIP</th>
                                <th>NO HP</th>
                                <th>NAMA BARANG</th>
                                <th>JUMLAH</th>
                                <th>TANGGAL</th>
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
                                <td>{{ $r->item->nama_barang }}</td>
                                <td>{{ $r->jumlah_request }}</td>
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
                                                data-name="{{ $r->nama_pemohon }}"
                                                data-item="{{ $r->item->nama_barang }}"
                                                data-qty="{{ $r->jumlah_request }}">
                                                Approve
                                            </button>
                                            <button class="btn btn-sm btn-danger reject-btn"
                                                data-toggle="modal" data-target="#rejectModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama_pemohon }}"
                                                data-item="{{ $r->item->nama_barang }}">
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                    <p>Anda akan menyetujui permintaan barang berikut:</p>
                    <div class="alert alert-info">
                        <strong id="approve-item-name"></strong><br>
                        Jumlah: <span id="approve-item-qty"></span><br>
                        Peminta: <span id="approve-requester-name"></span>
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

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
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
                    <p>Anda akan menolak permintaan barang berikut:</p>
                    <div class="alert alert-warning">
                        <strong id="reject-item-name"></strong><br>
                        Peminta: <span id="reject-requester-name"></span>
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
@endsection

@push('scripts')
<script>
$(function(){
    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        $('#approveForm').attr('action', '/dashboard/approvals/barang/' + id + '/approve');
        $('#approve-item-name').text($(this).data('item'));
        $('#approve-item-qty').text($(this).data('qty'));
        $('#approve-requester-name').text($(this).data('name'));
    });

    $('.reject-btn').on('click', function(){
        var id = $(this).data('id');
        $('#rejectForm').attr('action', '/dashboard/approvals/barang/' + id + '/reject');
        $('#reject-item-name').text($(this).data('item'));
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
    background-color: #1f2937; /* Tetap gelap */
    color: #fff;
    font-weight: 600;
    text-align: center;
}
.table tbody tr td {
    background-color: #ffffff; /* Isi tabel putih */
    color: #333; /* Teks isi abu gelap */
    vertical-align: middle;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f8f9fa; /* Sedikit abu muda */
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
