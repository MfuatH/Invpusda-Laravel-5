<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Makanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('images/background.jpeg'); /* ganti sesuai lokasi gambar */
            background-size: cover;
            background-position: center;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .outer-container {
            max-width: 1850px;
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .left-panel {
            background: transparent;
            padding: 30px;
            text-align: center;
            color: white;
        }

        .left-panel img.logo {
            max-width: 250px;
            margin-bottom: 20px;
        }

        .left-panel h2 {
            font-size: 2rem;
            font-weight: 700;
        }

        .left-panel img.illustration {
            max-width: 90%;
            margin-top: 20px;
        }

        /* Panel kanan form */
        .right-panel {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
        }

        .form-group {
            position: relative;
        }

        .form-group i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #777;
            z-index: 2; /* Pastikan icon di atas input file */
        }

        .form-control {
            padding-left: 40px;
            background: #f4f7f6;
            border: none;
            height: 45px;
            border-radius: 8px;
        }
        
        /* Penyesuaian untuk input file agar icon terlihat */
        .form-control[type="file"] {
            padding-top: 10px;
        }

        textarea.form-control {
            height: auto;
            padding-top: 12px;
        }

        .btn-custom {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 8px;
        }

        /* Style untuk contoh kode */
        code {
            background-color: #e9ecef;
            padding: 2px 4px;
            border-radius: 4px;
            color: #c7254e;
        }

        @media(max-width: 768px) {
            .right-panel {
                border-radius: 0;
            }
            .left-panel img.illustration {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="outer-container">
    <div class="row g-0">

        <div class="col-md-7 left-panel">
            <div class="p-3">
                <h4 class="text-white font-weight-bold">Permintaan Catering Terbaru</h4>
                @php
                    // Use provided $caterings or fallback to a small query (avoid heavy queries in view)
                    $caterings = isset($caterings) ? $caterings : \App\Catering::latest()->limit(6)->get();
                @endphp

                @if($caterings->count() > 0)
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Keperluan</th>
                                <th>Tgl Kegiatan</th>
                                <th>Jumlah</th>
                                <th>Tgl Kirim</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($caterings as $c)
                            <tr>
                                <td class="align-middle" style="min-width:120px">{{ \Illuminate\Support\Str::limit($c->nama_pemesan, 18) }}</td>
                                <td class="align-middle" style="min-width:120px">{{ \Illuminate\Support\Str::limit($c->keperluan, 20) }}</td>
                                <td class="align-middle">{{ \Carbon\Carbon::parse($c->tanggal_kegiatan)->format('d M Y') }}</td>
                                <td class="align-middle text-center">{{ $c->jumlah_peserta }}</td>
                                <td class="align-middle small">{{ \Carbon\Carbon::parse($c->created_at)->format('d M Y') }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-pill {{ $c->status == 'pending' ? 'badge-soft-warning' : ($c->status == 'approved' ? 'badge-soft-success' : 'badge-soft-danger') }}">{{ ucfirst($c->status) }}</span>
                                </td>
                                <td class="align-middle">
                                    <button type="button" 
                                        class="btn btn-sm btn-light lihat-btn"
                                        data-bs-toggle="modal" data-bs-target="#viewCateringModal"
                                        data-id="{{ $c->id }}"
                                        data-name="{{ e($c->nama_pemesan) }}"
                                        data-nip="{{ e($c->nip) }}"
                                        data-keperluan="{{ e($c->keperluan) }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($c->tanggal_kegiatan)->format('d-m-Y H:i') }}"
                                        data-tempat="{{ e($c->tempat) }}"
                                        data-peserta="{{ $c->jumlah_peserta }}"
                                        data-konsumsi="{{ e($c->jenis_konsumsi_string) }}"
                                        data-nota_url="{{ $c->nota_dinas_url ?? '#' }}"
                                        data-keterangan="{{ e($c->keterangan ?? '-') }}"
                                    >Lihat</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="mt-3 text-center text-white-50">
                    <p class="mb-0">Belum ada permintaan catering.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-4 p-3 offset-lg-1">
            <div class="right-panel">

                <h4 class="mb-4 fw-bold">Pemesanan Makanan</h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('catering.store') }}" method="POST" enctype="multipart/form-data">
                    
                    {{ csrf_field() }}

                    <div class="form-group mb-3">
                        <i class="fa fa-user"></i>
                        <input type="text" name="nama_pemesan" class="form-control" placeholder="Nama Pemesan" required value="{{ old('nama_pemesan') }}">
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-id-card"></i>
                        <input type="text" name="nip" class="form-control" placeholder="NIP (Opsional)" value="{{ old('nip') }}">
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-file-alt"></i>
                        <input type="text" name="keperluan" class="form-control" placeholder="Keperluan" required value="{{ old('keperluan') }}">
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-calendar"></i>
                        <div class="d-flex gap-2">
                            @php
                                $oldTanggal = old('tanggal_kegiatan');
                                $oldDate = '';
                                $oldTime = '';
                                if ($oldTanggal) {
                                    try {
                                        $dt = \Carbon\Carbon::parse($oldTanggal);
                                        $oldDate = $dt->format('Y-m-d');
                                        $oldTime = $dt->format('H:i');
                                    } catch (\Exception $e) {
                                        $oldDate = '';
                                        $oldTime = '';
                                    }
                                }
                            @endphp

                            <input type="date" id="tanggal_date" class="form-control" style="max-width:40%;" value="{{ $oldDate }}" required>
                            <input type="text" id="tanggal_time" class="form-control" style="max-width:40%;" value="{{ $oldTime }}" required placeholder="HH:mm">
                            <input type="hidden" name="tanggal_kegiatan" id="tanggal_kegiatan_hidden" value="{{ $oldTanggal ?? '' }}">
                        </div>
                        <small class="form-text text-muted">Pilih tanggal dan jam (format 24 jam).</small>
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-location-dot"></i>
                        <input type="text" name="tempat" class="form-control" placeholder="Tempat" required value="{{ old('tempat') }}">
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-users"></i>
                        <input type="number" name="jumlah_peserta" class="form-control" placeholder="Jumlah Peserta" required value="{{ old('jumlah_peserta') }}">
                    </div>

                    <label class="mb-1">Jenis Konsumsi</label><br>
                    <div class="d-flex gap-3 mb-3">
                        <label><input type="checkbox" name="jenis_konsumsi[]" value="Makan"> Makan</label>
                        <label><input type="checkbox" name="jenis_konsumsi[]" value="Minum"> Minum</label>
                        <label><input type="checkbox" name="jenis_konsumsi[]" value="Snack"> Snack</label>
                    </div>

                    <label for="notaDinas" class="form-label" style="font-size: 0.9rem; font-weight: 500;">Upload Nota Dinas (Wajib)</label>
                    
                    <small class="form-text text-muted d-block mb-1">
                        <strong>Penting:</strong> Nama file tidak boleh pakai spasi/karakter spesial (!@#$).
                        <br>Contoh benar: <code>NotaDinas_Rapat_2025.pdf</code>
                    </small>
                    <div class="form-group mb-3">
                        <i class="fa fa-paperclip"></i>
                        <input type="file" name="nota_dinas_file" class="form-control" id="notaDinas" required>
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-comment"></i>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('landing-page') }}" class="btn btn-custom btn-custom-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary btn-custom">Kirim Pemesanan</button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

        <!-- Modal: View Catering Details -->
        <div class="modal fade" id="viewCateringModal" tabindex="-1" aria-labelledby="viewCateringModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCateringModalLabel">Detail Pemesanan Catering</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><small class="text-muted">Pemesan</small><br><strong id="view-name"></strong></p>
                                <p><small class="text-muted">NIP</small><br><strong id="view-nip"></strong></p>
                                <p><small class="text-muted">Keperluan</small><br><strong id="view-keperluan"></strong></p>
                                <p><small class="text-muted">Tanggal Kegiatan</small><br><strong id="view-tanggal"></strong></p>
                            </div>
                            <div class="col-md-6">
                                <p><small class="text-muted">Tempat</small><br><strong id="view-tempat"></strong></p>
                                <p><small class="text-muted">Jumlah Peserta</small><br><strong id="view-peserta"></strong></p>
                                <p><small class="text-muted">Jenis Konsumsi</small><br><strong id="view-konsumsi"></strong></p>
                                <p><small class="text-muted">Keterangan</small><br><strong id="view-keterangan"></strong></p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="view-nota-btn" class="btn btn-outline-primary btn-sm">Lihat File Nota</button>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="" class="btn btn-outline-primary btn-sm">Hapus</button>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="" class="btn btn-outline-primary btn-sm">Laporan</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS bundle (includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
        (function(){
            var viewModalEl = document.getElementById('viewCateringModal');
            if (!viewModalEl) return;

            viewModalEl.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                if (!button) return;

                // Read data attributes
                var name = button.getAttribute('data-name') || '-';
                var nip = button.getAttribute('data-nip') || '-';
                var keperluan = button.getAttribute('data-keperluan') || '-';
                var tanggal = button.getAttribute('data-tanggal') || '-';
                var tempat = button.getAttribute('data-tempat') || '-';
                var peserta = button.getAttribute('data-peserta') || '-';
                var konsumsi = button.getAttribute('data-konsumsi') || '-';
                var keterangan = button.getAttribute('data-keterangan') || '-';
                var notaUrl = button.getAttribute('data-nota_url') || '#';

                // Populate modal fields
                document.getElementById('view-name').textContent = name;
                document.getElementById('view-nip').textContent = nip;
                document.getElementById('view-keperluan').textContent = keperluan;
                document.getElementById('view-tanggal').textContent = tanggal;
                document.getElementById('view-tempat').textContent = tempat;
                document.getElementById('view-peserta').textContent = peserta;
                document.getElementById('view-konsumsi').textContent = konsumsi;
                document.getElementById('view-keterangan').textContent = keterangan;

                var notaBtn = document.getElementById('view-nota-btn');
                if (notaBtn) {
                    notaBtn.dataset.url = notaUrl;
                }
            });

            // Nota button opens file in new tab or alerts if missing
            document.addEventListener('click', function(e){
                if (e.target && e.target.id === 'view-nota-btn'){
                    var url = e.target.dataset.url || '#';
                    if (url && url !== '#') {
                        window.open(url, '_blank');
                    } else {
                        alert('File nota dinas tidak tersedia.');
                    }
                }
            });
        })();
        </script>

        <script>
        // Combine date + time into hidden input so backend gets a single datetime string
        (function(){
            var form = document.querySelector('form[action="{{ route('catering.store') }}"]');
            if (!form) return;

            var dateInput = document.getElementById('tanggal_date');
            var timeInput = document.getElementById('tanggal_time');
            var hidden = document.getElementById('tanggal_kegiatan_hidden');

            function setHidden() {
                if (!dateInput || !timeInput || !hidden) return;
                var d = dateInput.value;
                var t = timeInput.value;
                if (d && t) {
                    // Format to 'Y-m-d H:i:00' which Laravel/DB accepts
                    hidden.value = d + ' ' + t + ':00';
                } else if (d) {
                    hidden.value = d + ' 00:00:00';
                }
            }

            // Update hidden on change
            if (dateInput) dateInput.addEventListener('change', setHidden);
            if (timeInput) timeInput.addEventListener('change', setHidden);

            // Ensure hidden is up-to-date before submit
            form.addEventListener('submit', function(e){
                setHidden();
            });

            // Initialize once
            setHidden();
        })();
        </script>

        <!-- flatpickr time picker (24-hour) -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            (function(){
                var timeEl = document.getElementById('tanggal_time');
                if (!timeEl) return;
                flatpickr(timeEl, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 1,
                    defaultDate: timeEl.value || null,
                });
            })();
        </script>

</body>
</html>