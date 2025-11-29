@extends('layouts.app')

@section('title', 'Approval Peminjaman Kendaraan')

@section('content')
<div class="container-fluid px-4">

    {{-- Header Judul --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-car text-primary mr-2"></i> Approval Peminjaman Kendaraan
        </h4>
    </div>

    {{-- 
        [DIHAPUS] Bagian Alert/Notifikasi Session dihapus dari sini 
        karena sudah ditangani otomatis oleh file layouts/app.blade.php.
        Hal ini mencegah notifikasi muncul ganda (double).
    --}}

    {{-- Card Table --}}
    <div class="card shadow-sm w-100">
        <div class="card-body">
            @if ($requests->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-car-side fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted mb-0">
                        Tidak ada permintaan kendaraan yang menunggu persetujuan.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>PEMOHON</th>
                                <th>KONTAK</th>
                                <th>KENDARAAN</th>
                                <th>JADWAL</th>
                                <th>KEPERLUAN</th>
                                <th>STATUS</th>
                                <th style="min-width: 160px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $r)
                            <tr>
                                <td class="text-left">
                                    <strong>{{ $r->nama }}</strong><br>
                                    <small class="text-muted">NIP: {{ $r->nip ?? '-' }}</small>
                                </td>
                                <td>{{ $r->no_hp ?? '-' }}</td>
                                <td>
                                    @if($r->kendaraan)
                                        <span class="badge badge-light border">
                                            {{ $r->kendaraan->jenis }} <br> 
                                            <small>{{ $r->kendaraan->plat_no }}</small>
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="small text-left">
                                    <div class="text-nowrap"><i class="fas fa-arrow-circle-right text-success mr-1"></i> {{ \Carbon\Carbon::parse($r->tanggal_ambil)->format('d/m/Y H:i') }}</div>
                                    <div class="text-nowrap"><i class="fas fa-arrow-circle-left text-danger mr-1"></i> {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="text-left" style="max-width: 200px;">
                                    {{ \Illuminate\Support\Str::limit($r->urgensi, 50) }}
                                </td>
                                <td>
                                    @if($r->status === 'approved') 
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($r->status === 'rejected') 
                                        <span class="badge badge-danger">Rejected</span>
                                    @elseif($r->status === 'completed') 
                                        <span class="badge badge-info">Completed</span>
                                    @else 
                                        <span class="badge badge-warning text-dark">Pending</span> 
                                    @endif
                                </td>
                                <td>
                                    @if($r->status === 'pending')
                                        <div class="d-flex justify-content-center">
                                            {{-- TOMBOL TERIMA --}}
                                            <button class="btn btn-sm btn-success mr-2 approve-btn font-weight"
                                                data-toggle="modal" data-target="#approveModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama }}"
                                                data-nip="{{ $r->nip }}"
                                                data-hp="{{ $r->no_hp }}"
                                                data-vehicle="{{ $r->kendaraan ? $r->kendaraan->jenis . ' - ' . $r->kendaraan->plat_no : '-' }}"
                                                data-urgensi="{{ $r->urgensi }}"
                                                data-start="{{ \Carbon\Carbon::parse($r->tanggal_ambil)->format('d/m/Y H:i') }}"
                                                data-end="{{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d/m/Y H:i') }}">
                                                Terima
                                            </button>
                                            
                                            {{-- TOMBOL TOLAK --}}
                                            <button class="btn btn-sm btn-danger reject-btn font-weight"
                                                data-toggle="modal" data-target="#rejectModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama }}"
                                                data-vehicle="{{ $r->kendaraan ? $r->kendaraan->jenis . ' - ' . $r->kendaraan->plat_no : '-' }}">
                                                Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
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
{{-- MODAL APPROVE --}}
{{-- =================================================================== --}}
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-dark">
                        <i class="fas fa-check-circle text-success mr-2"></i> Setujui Peminjaman
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                
                <div class="modal-body bg-white">
                    {{-- Detail Readonly --}}
                    <div class="row">
                        <div class="col-md-6">
                            <label class="small text-muted">Pemohon</label>
                            <input type="text" class="form-control bg-light mb-2" id="app-name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">No HP (WA)</label>
                            <input type="text" class="form-control bg-light mb-2" id="app-hp" readonly>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label class="small text-muted">Kendaraan</label>
                        <input type="text" class="form-control bg-light font-weight-bold" id="app-vehicle" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="small text-muted">Tanggal Ambil</label>
                            <input type="text" class="form-control bg-light" id="app-start" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Tanggal Kembali</label>
                            <input type="text" class="form-control bg-light" id="app-end" readonly>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label class="font-weight-bold text-success">Catatan Admin (Untuk WA)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Contoh: Silakan ambil kunci di pos satpam."></textarea>
                        <small class="text-muted">Pesan ini akan dikirim ke WhatsApp pemohon.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-success font-weight-bold" type="submit">
                        <i class="fab fa-whatsapp mr-1"></i> Setujui & Kirim WA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL REJECT --}}
{{-- =================================================================== --}}
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-times-circle mr-1"></i> Konfirmasi Penolakan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak permintaan ini:</p>
                    <div class="alert alert-warning border-warning">
                        <strong>Pemohon:</strong> <span id="rej-name"></span><br>
                        <strong>Kendaraan:</strong> <span id="rej-vehicle"></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-danger">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="note" class="form-control" rows="3" required placeholder="Contoh: Kendaraan sedang dalam perawatan..."></textarea>
                        <small class="text-muted">Alasan ini akan dikirim ke WhatsApp pemohon.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="submit">
                        <i class="fab fa-whatsapp mr-1"></i> Tolak & Kirim WA
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
    // Template Route
    // Pastikan route ini ada di web.php
    const approveTemplate = '{{ route("kendaraan.approve", ["id" => "PLACEHOLDER"]) }}';
    const rejectTemplate = '{{ route("kendaraan.reject", ["id" => "PLACEHOLDER"]) }}';

    // Handle Tombol Approve
    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        
        // Ganti PLACEHOLDER dengan ID asli
        var url = approveTemplate.replace('PLACEHOLDER', id);
        $('#approveForm').attr('action', url);
        
        // Isi Data ke Modal
        $('#app-name').val($(this).data('name'));
        $('#app-nip').val($(this).data('nip'));
        $('#app-hp').val($(this).data('hp'));
        $('#app-vehicle').val($(this).data('vehicle'));
        $('#app-urgensi').val($(this).data('urgensi'));
        $('#app-start').val($(this).data('start'));
        $('#app-end').val($(this).data('end'));
    });

    // Handle Tombol Reject
    $('.reject-btn').on('click', function(){
        var id = $(this).data('id');
        
        var url = rejectTemplate.replace('PLACEHOLDER', id);
        $('#rejectForm').attr('action', url);
        
        $('#rej-name').text($(this).data('name'));
        $('#rej-vehicle').text($(this).data('vehicle'));
    });
});
</script>
@endpush

@push('styles')
<style>
    .table-bordered th, .table-bordered td { border: 1px solid #dee2e6 !important; }
    .table thead th { background-color: #343a40; color: #fff; border-color: #454d55; }
    .btn-sm { padding: 0.25rem 0.5rem; }
</style>
@endpush