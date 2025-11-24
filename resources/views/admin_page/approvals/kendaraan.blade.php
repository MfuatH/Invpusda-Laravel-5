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
                    <table class="table table-striped align-middle text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>PEMOHON</th>
                                <th>NIP</th>
                                <th>NO HP</th>
                                <th>KENDARAAN</th>
                                <th>JADWAL</th>
                                <th>KEPERLUAN</th>
                                <th>STATUS</th>
                                <th style="width: 150px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $r)
                            <tr>
                                <td class="font-weight-bold">{{ $r->nama }}</td>
                                <td>{{ $r->nip ?? '-' }}</td>
                                <td>{{ $r->no_hp ?? '-' }}</td>
                                <td>
                                    @if($r->kendaraan)
                                        <span class="badge badge-info p-2" style="font-size: 0.9em;">
                                            {{ $r->kendaraan->nama_barang ?? $r->kendaraan_id }} {{-- Sesuaikan jika pakai relasi --}}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="small text-left">
                                    <div class="text-nowrap"><i class="fas fa-arrow-circle-right text-success mr-1"></i> {{ \Carbon\Carbon::parse($r->tanggal_ambil)->format('d/m/Y H:i') }}</div>
                                    <div class="text-nowrap"><i class="fas fa-arrow-circle-left text-danger mr-1"></i> {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="text-left" style="max-width: 200px;">{{ \Illuminate\Support\Str::limit($r->urgensi, 50) }}</td>
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
                                    @if($r->status === 'pending')
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-success mr-2 approve-btn"
                                                data-toggle="modal" data-target="#approveModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama }}"
                                                data-nip="{{ $r->nip }}"
                                                data-hp="{{ $r->no_hp }}"
                                                data-vehicle="{{ $r->kendaraan->nama_barang ?? $r->kendaraan_id }}"
                                                data-urgensi="{{ $r->urgensi }}"
                                                data-start="{{ \Carbon\Carbon::parse($r->tanggal_ambil)->format('d/m/Y H:i') }}"
                                                data-end="{{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d/m/Y H:i') }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            
                                            <button class="btn btn-sm btn-danger reject-btn"
                                                data-toggle="modal" data-target="#rejectModal"
                                                data-id="{{ $r->id }}"
                                                data-name="{{ $r->nama }}"
                                                data-vehicle="{{ $r->kendaraan->nama_barang ?? $r->kendaraan_id }}">
                                                <i class="fas fa-times"></i>
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
{{-- MODAL APPROVE (DETAIL SEPERTI GAMBAR) --}}
{{-- =================================================================== --}}
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> {{-- Modal Besar --}}
        <div class="modal-content">
            <form id="approveForm" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-dark">
                        <i class="fas fa-file-alt mr-2"></i> Detail Peminjaman
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                
                <div class="modal-body bg-white">
                    {{-- Form Readonly (Tampilan seperti gambar) --}}
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control bg-light" id="app-name" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light" id="app-nip" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light" id="app-hp" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-exclamation-circle"></i></span>
                            </div>
                            <input type="text" class="form-control bg-light" id="app-urgensi" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-car"></i></span>
                            </div>
                            <input type="text" class="form-control bg-light font-weight-bold" id="app-vehicle" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="small text-muted mb-0">Tanggal Ambil</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-check"></i></span>
                                </div>
                                <input type="text" class="form-control bg-light" id="app-start" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-0">Tanggal Kembali</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-times"></i></span>
                                </div>
                                <input type="text" class="form-control bg-light" id="app-end" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>
                    {{-- Input Admin --}}
                    <div class="form-group">
                        <label class="font-weight-bold text-success">Catatan Persetujuan (Opsional)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Misal: Kunci ada di pos satpam..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button class="btn btn-primary font-weight-bold" type="submit">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Request (Setujui)
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
    // Template Route
    const approveTemplate = '{{ route("kendaraan.approve", ["id" => "PLACEHOLDER"]) }}';
    const rejectTemplate = '{{ route("kendaraan.reject", ["id" => "PLACEHOLDER"]) }}';

    // Handle Tombol Approve
    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        
        // Set Action URL Form
        $('#approveForm').attr('action', approveTemplate.replace('PLACEHOLDER', id));
        
        // Isi Data ke Modal (Readonly Inputs)
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
        
        // Set Action URL Form
        $('#rejectForm').attr('action', rejectTemplate.replace('PLACEHOLDER', id));
        
        // Isi Data Ringkas
        $('#rej-name').text($(this).data('name'));
        $('#rej-vehicle').text($(this).data('vehicle'));
    });
});
</script>
@endpush

@push('styles')
<style>
    /* Styling Tabel */
    .table { font-size: 0.9rem; background: #fff; }
    .table thead th { 
        background-color: #343a40; /* Dark Header */
        color: #fff; 
        border-color: #454d55;
        vertical-align: middle;
    }
    .table tbody tr td {
        vertical-align: middle;
    }
    
    /* Styling Modal seperti Form */
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
        width: 45px;
        justify-content: center;
    }
    .form-control[readonly] {
        background-color: #f8f9fa;
        border-left: none;
        opacity: 1; /* Agar teks tetap hitam jelas */
    }
    .modal-header { border-bottom: 1px solid #dee2e6; }
    .modal-footer { border-top: 1px solid #dee2e6; }
</style>
@endpush