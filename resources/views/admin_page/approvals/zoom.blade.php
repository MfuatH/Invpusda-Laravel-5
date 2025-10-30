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

    {{-- Session Alert (PENTING: Pastikan ini ada untuk notifikasi sukses/error) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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
                                <td>
                                    <span class="badge 
                                        @if($r->status === 'approved') badge-success
                                        @elseif($r->status === 'rejected') badge-danger
                                        @else badge-warning @endif
                                        font-weight-bold">{{ ucfirst($r->status) }}</span>
                                </td>
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
                                        @if($r->status === 'approved' && $r->link_zoom)
                                            <a href="{{ $r->link_zoom }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-link"></i> Link Tersedia
                                            </a>
                                        @else
                                            <span class="text-secondary">-</span>
                                        @endif
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
                    <div class="small text-muted">
                        Menampilkan **{{ $requests->firstItem() }}** sampai **{{ $requests->lastItem() }}** dari total **{{ $requests->total() }}** permintaan
                    </div>
                    {{ $requests->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                {{ csrf_field() }}
                {{-- PENTING: Hapus @method('PUT') karena Anda menggunakan Route::post --}}
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">Konfirmasi Persetujuan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
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
                        <input type="url" name="link_zoom" class="form-control" required placeholder="https://zoom.us/j/...">
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

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                {{ csrf_field() }}
                {{-- PENTING: Hapus @method('PUT') karena Anda menggunakan Route::post --}}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
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
    
    // Gunakan fungsi untuk mendapatkan URL APPROVE final
    // Kita akan biarkan route helper menghasilkan URL lengkap, dan kita hanya perlu ID-nya.
    // Contoh template URL: http://127.0.0.1:8000/dashboard/zoom/PLACEHOLDER/approve
    const approveTemplate = '{{ route("zoom.requests.approve", ["reqZoom" => "PLACEHOLDER"]) }}';
    
    // Gunakan fungsi untuk mendapatkan URL REJECT final
    // Contoh template URL: http://127.0.0.1:8000/dashboard/zoom/requests/PLACEHOLDER/reject
    const rejectTemplate = '{{ route("zoom.requests.reject", ["reqZoom" => "PLACEHOLDER"]) }}';

    $('.approve-btn').on('click', function(){
        var id = $(this).data('id');
        
        // Ganti PLACEHOLDER dengan ID yang sebenarnya
        const finalApproveUrl = approveTemplate.replace('PLACEHOLDER', id);
        
        // Mengatur action form
        $('#approveForm').attr('action', finalApproveUrl);
        
        $('#approve-meeting-name').text($(this).data('meeting'));
        $('#approve-meeting-time').text($(this).data('time'));
        $('#approve-requester-name').text($(this).data('name'));
    });

    $('.reject-btn').on('click', function(){
        var id = $(this).data('id');
        
        // Ganti PLACEHOLDER dengan ID yang sebenarnya
        const finalRejectUrl = rejectTemplate.replace('PLACEHOLDER', id);
        
        // Mengatur action form
        $('#rejectForm').attr('action', finalRejectUrl);
        
        $('#reject-meeting-name').text($(this).data('meeting'));
        $('#reject-requester-name').text($(this).data('name'));
    });
});
</script>
@endpush

@push('styles')
<style>
/* ... (Style CSS Anda) ... */
.table { font-size: 14px; background: #fff; }
.table thead th { background-color: #2c3e50; color: #fff; font-weight: 600; text-align: center; }
.table tbody tr td { vertical-align: middle; }
.table-striped tbody tr:nth-of-type(odd) { background-color: #f8f9fa; }
.badge { border-radius: 10px; font-size: 0.8rem; padding: 6px 10px; }

.badge-warning { background-color: #ffc107; color: #333; }
.badge-success { background-color: #28a745; color: #fff; }
.badge-danger { background-color: #dc3545; color: #fff; }
.badge-info { background-color: #17a2b8; color: #fff; }

.modal-header.bg-success { background-color: #28a745 !important; }
.modal-header.bg-danger { background-color: #dc3545 !important; }
.modal-content { border-radius: 10px; }
</style>
@endpush