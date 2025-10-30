@extends('layouts.app')

@section('title', 'Approval Permintaan Barang')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle ?? 'Approval Permintaan Barang' }}</h1>
    </div>

    @if (Auth::user()->role === 'admin_barang')
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-info-circle mr-1"></i> Anda hanya melihat daftar permintaan barang dari Bidang Anda saja.
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-clipboard-list mr-1"></i>
                Daftar Permintaan Barang
            </h6>
        </div>
        <div class="card-body">
            @if ($requests->isEmpty())
                <p class="text-center text-muted mb-0">Tidak ada permintaan barang yang menunggu persetujuan saat ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 40px">No</th>
                                <th>Nama Pemohon</th>
                                <th>NIP</th>
                                <th>No. HP</th>
                                <th>Bidang</th>
                                <th>Nama Barang</th>
                                <th style="width: 100px">Jumlah</th>
                                <th style="width: 120px">Tanggal</th>
                                <th style="width: 90px">Status</th>
                                <th style="width: 180px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $index => $request)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $request->nama_pemohon }}</td>
                                <td>{{ $request->nip ?? '-' }}</td>
                                <td>{{ $request->no_hp ?? '-' }}</td>
                                <td>{{ $request->bidang->nama ?? '-' }}</td>
                                <td>{{ $request->item->nama_barang }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $request->jumlah_request }} {{ $request->item->satuan }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}<br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($request->created_at)->format('H:i') }} WIB
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($request->status === 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($request->status === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif($request->status === 'received')
                                        <span class="badge badge-info">Diterima</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->status === 'pending')
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm approve-btn" 
                                                data-toggle="modal" data-target="#approveModal" 
                                                data-id="{{ $request->id }}"
                                                data-name="{{ $request->nama_pemohon }}"
                                                data-item="{{ $request->item->nama_barang }}"
                                                data-qty="{{ $request->jumlah_request }} {{ $request->item->satuan }}">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm reject-btn"
                                                data-toggle="modal" data-target="#rejectModal"
                                                data-id="{{ $request->id }}"
                                                data-name="{{ $request->user_name }}"
                                                data-item="{{ $request->item_name }}">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </div>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm" disabled>
                                            Sudah diproses
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($requests->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
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
            <form action="" method="POST" id="approveForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Konfirmasi Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menyetujui permintaan barang:</p>
                    <div class="alert alert-info">
                        <strong id="approve-item-name"></strong><br>
                        Jumlah: <span id="approve-item-qty"></span><br>
                        Peminta: <span id="approve-requester-name"></span>
                    </div>
                    <div class="form-group">
                        <label for="approve_note">Catatan (opsional)</label>
                        <textarea name="note" id="approve_note" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Setujui Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="rejectForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak permintaan barang:</p>
                    <div class="alert alert-warning">
                        <strong id="reject-item-name"></strong><br>
                        Peminta: <span id="reject-requester-name"></span>
                    </div>
                    <div class="form-group">
                        <label for="reject_note">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="note" id="reject_note" rows="3" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i> Tolak Permintaan
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
    // Handle Approve Modal
    $('.approve-btn').click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var item = $(this).data('item');
        var qty = $(this).data('qty');
        
        $('#approve-requester-name').text(name);
        $('#approve-item-name').text(item);
        $('#approve-item-qty').text(qty);
        $('#approveForm').attr('action', '/dashboard/approvals/barang/' + id + '/approve');
    });

    // Handle Reject Modal
    $('.reject-btn').click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var item = $(this).data('item');
        
        $('#reject-requester-name').text(name);
        $('#reject-item-name').text(item);
        $('#rejectForm').attr('action', '/dashboard/approvals/barang/' + id + '/reject');
    });
});
</script>
@endpush

@push('styles')
<style>
.badge { font-size: 90%; }
.table td { vertical-align: middle; }
.btn-group .btn { margin-right: 0.25rem; }
.btn-group .btn:last-child { margin-right: 0; }
</style>
@endpush