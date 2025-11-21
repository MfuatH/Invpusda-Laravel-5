<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen Laporan</title>

    {{-- Bootstrap & Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        /* ... (Semua CSS Anda sudah benar) ... */
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('/images/background.jpeg');
            background-size: cover;
            background-position: center;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .outer-container {
            max-width: 1350px;
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        .left-panel {
            background: transparent;
            padding: 30px;
            text-align: center;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%;
            min-height: 700px;
        }
        .left-panel img.logo {
            max-width: 250px;
            margin-bottom: 20px;
            align-self: center;
        }
        .left-panel h2 {
            font-size: 2rem;
            font-weight: 700;
        }
        .iframe-container {
            position: relative;
            height: 100%;
            width: 100%;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
        .btn-fullscreen {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 14px;
            transition: .2s;
            align-self: flex-start;
        }
        .btn-fullscreen:hover {
            background: rgba(0, 0, 0, 0.7);
        }
        .btn-fullscreen i {
            margin-right: 6px;
        }
        .document-preview-iframe {
            width: 100%;
            height: 100%;
            min-height: 600px;
            min-width: 700px;
            border-radius: 10px;
            border: none;
            flex-grow: 1;
            background: rgba(255,255,255,0.9);
        }
        .right-panel {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            height: 100%;
            min-height: 850px;
            overflow-y: auto;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .btn-custom {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 8px;
        }
        .btn-custom-secondary {
            background: #6c757d;
            color: white;
        }
        @media(max-width: 991px) {
            .right-panel, .left-panel {
                min-height: auto;
            }
            .right-panel {
                border-radius: 0 0 15px 15px;
                max-height: none;
            }
            .left-panel {
                border-radius: 15px 15px 0 0;
            }
        }
        .btn-upload-main {
            background: #0d6efd;
            color: #fff;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 25px;
            justify-content: center;
            width: 100%;
        }
        .btn-upload-main:hover {
            background: #0b5ed7;
            color: #fff;
        }
        .card-grey {
            background: #EEF1F5;
            padding: 20px;
            border-radius: 12px;
            flex-grow: 1;
        }
        .desc {
            font-size: 15px;
            color: #444;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .desc b {
            color: #333;
        }
        .desc ul {
            padding-left: 20px;
            margin-top: 5px;
            margin-bottom: 0;
        }
        .modal-bg {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-box {
            width: 80%;
            max-width: 550px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }
        .modal-header {
            background: #0d6efd;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        .modal-header .title {
            font-weight: 600;
        }
        .modal-header button {
            background: white;
            color: #0d6efd;
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }
        .modal-body-scroll {
            padding: 25px;
            overflow-y: auto;
            flex-grow: 1;
        }
        .form-group-modal {
            position: relative;
            margin-bottom: 15px;
        }
        .form-group-modal label {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            display: block;
        }
        .form-group-modal i {
            position: absolute;
            left: 15px;
            color: #777;
            z-index: 2;
            top: 55px; /* Disesuaikan agar di dalam input */
            transform: translateY(-50%);
        }
        .form-control-modal {
            padding-left: 45px !important;
            background: #f4f7f6;
            border: 1px solid #ced4da;
            height: 50px;
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-control-modal:focus {
            background: #fff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }
        textarea.form-control-modal {
            height: auto;
            min-height: 80px;
            padding-top: 15px;
            resize: vertical;
        }
        .form-group-modal textarea.form-control-modal + i {
             top: 48px; /* Disesuaikan untuk textarea */
             transform: none;
        }
        .file-input-wrapper-modal {
            position: relative;
            width: 100%;
            text-align: center;
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 25px;
            box-sizing: border-box;
            background: #fafafa;
            cursor: pointer;
            transition: .2s;
        }
        .file-input-wrapper-modal:hover {
            background: #f4f4f4;
            border-color: #007bff;
        }
        .file-input-wrapper-modal .file-input-icon-modal {
            font-size: 30px;
            color: #6c757d;
        }
        .file-input-wrapper-modal .file-input-text-modal {
            font-size: 15px;
            color: #555;
            font-weight: 500;
            margin-top: 8px;
        }
        .file-input-wrapper-modal input[type="file"] {
            position: absolute; left: 0; top: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;
        }
        .modal-submit-btn {
            background: #0d6efd;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .modal-submit-btn:hover {
            background: #0b5ed7;
        }
        .text-danger.small {
            font-size: 0.85em;
            margin-top: 5px;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>

<div class="outer-container">
    <div class="row g-0 h-100">

        {{-- LEFT PANEL (Iframe) --}}
        <div class="col-lg-6 left-panel">
            <img src="/images/logo.png" class="logo" alt="Logo">
            <div class="iframe-container">
                <h3>Contoh Dokumen</h3>
                <iframe src="/sample/Contoh_Dokumen_Final.pdf" class="document-preview-iframe" id="preview-iframe" title="Contoh Dokumen"></iframe>
            </div>
        </div>

        {{-- RIGHT PANEL (Info + Tombol) --}}
        <div class="col-lg-5 p-3 offset-lg-1">
            <div class="right-panel">
                <div> @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any() && !session('error_modal_open'))
                        <div class="alert alert-danger small p-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-grey">
                        <h3>Upload Dokumen</h3>
                        <div class="desc">
                            <b>Format Pembuatan Laporan Akhir:</b><br>
                            Pastikan file Anda sudah sesuai dengan format yang ditentukan.
                            <br><br>
                            <b>Urutan dokumen:</b>
                            <ul>
                                <li>Presensi</li>
                                <li>Notulen</li>
                                <li>Nodin</li>
                                <li>Lampiran pendukung</li>
                            </ul>
                            <small class="form-text text-muted d-block mb-1">
                            <strong>Penting:</strong> Nama file tidak boleh pakai spasi/karakter spesial (!@#$).
                            <br>Contoh benar: <code>NotaDinas_Rapat_2025.pdf</code>
                    </small>
                        </div>
                    </div>
                    
                    <button type="button" class="btn-upload-main" onclick="openUploadModal()">
                        <i class="fas fa-plus"></i> Upload Laporan
                    </button>
                </div> <div class="text-end mt-4">
                    {{--buat menjadi tombol penyelesaian request, merubah status dari approved ke completed --}}
                    <a href="{{ route('landing-page') }}" class="btn btn-custom btn-custom-secondary">Selesaikan Request</a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ================================================== --}}
{{-- === MODAL FORM UPLOAD (YANG AKAN POP-UP) === --}}
{{-- ================================================== --}}
<div class="modal-bg" id="uploadModal">
    <div class="modal-box">
        <div class="modal-header">
            <span class="title">Form Upload Laporan</span>
            <button class="modal-close-btn" onclick="closeUploadModal()">Tutup</button>
        </div>
        
        <div class="modal-body-scroll">
            
            {{-- ================================================ --}}
            {{-- === PERBAIKAN FORM ADA DI SINI === --}}
            {{-- ================================================ --}}
            <form action="{{ route('request.store.LaporanRapat') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                {{-- 1. Field Pengunggah --}}
                <div class="form-group-modal">
                    <label for="pengunggah" class="form-label">Nama Pengunggah <span class="text-danger">*</span></label>
                    <div class="input-with-icon"> {{-- Wrapper untuk input dan ikon --}}
                        <i class="fa fa-user"></i>
                        <input type="text" id="pengunggah" name="pengunggah" 
                               class="form-control-modal {{ $errors->has('pengunggah') ? 'is-invalid' : '' }}" 
                               placeholder="Nama Lengkap" required value="{{ old('pengunggah') }}">
                    </div>
                    @if ($errors->has('pengunggah'))
                        <div class="text-danger small">{{ $errors->first('pengunggah') }}</div>
                    @endif
                </div>

                {{-- 2. Field NIP --}}
                <div class="form-group-modal">
                    <label for="nip" class="form-label">NIP <span class="text-muted">(Opsional)</span></label>
                    <div class="input-with-icon">
                        <i class="fa fa-id-card"></i>
                        <input type="text" id="nip" name="nip" 
                               class="form-control-modal {{ $errors->has('nip') ? 'is-invalid' : '' }}" 
                               placeholder="NIP" value="{{ old('nip') }}">
                    </div>
                    @if ($errors->has('nip'))
                        <div class="text-danger small">{{ $errors->first('nip') }}</div>
                    @endif
                </div>

                {{-- 3. Field Keterangan --}}
                <div class="form-group-modal">
                    <label for="keterangan" class="form-label">Keterangan <span class="text-muted">(Opsional)</span></label>
                    <div class="input-with-icon">
                        <i class="fa fa-comment"></i>
                        <textarea id="keterangan" name="keterangan" rows="3" 
                                  class="form-control-modal {{ $errors->has('keterangan') ? 'is-invalid' : '' }}" 
                                  placeholder="Deskripsi singkat tentang dokumen">{{ old('keterangan') }}</textarea>
                    </div>
                    @if ($errors->has('keterangan'))
                        <div class="text-danger small">{{ $errors->first('keterangan') }}</div>
                    @endif
                </div>

                {{-- 4. Field File --}}
                <div class="form-group-modal">
                    <label for="fileInputModal" class="form-label" style="margin-bottom: 8px;">File <span class="text-danger">(Wajib)</span></label>
                    
                    <label class="file-input-wrapper-modal" for="fileInputModal">
                        <div class="file-input-icon-modal"><i class="fas fa-cloud-upload"></i></div>
                        <div class="file-input-text-modal" id="file-input-text-modal">Klik di sini untuk memilih file</div>
                        <input type="file" id="fileInputModal" name="file" accept=".pdf,.jpg,.jpeg,.png" 
                               class="{{ $errors->has('file') ? 'is-invalid' : '' }}" onchange="updateFileNameModal()" required>
                    </label>
                    @if ($errors->has('file'))
                        <div class="text-danger small">{{ $errors->first('file') }}</div>
                    @endif
                </div>

                <button type="submit" class="modal-submit-btn">
                    <i class="fas fa-paper-plane"></i> Upload Laporan
                </button>
            </form>
        </div>
    </div>
</div>


<script>
    // === SCRIPT UNTUK MODAL FORM UPLOAD ===
    function openUploadModal() {
        document.getElementById("uploadModal").style.display = "flex";
    }
    function closeUploadModal() {
        document.getElementById("uploadModal").style.display = "none";
        var invalidInputs = document.querySelectorAll('.is-invalid');
        for(var i = 0; i < invalidInputs.length; i++) {
            invalidInputs[i].classList.remove('is-invalid');
        }
        var errorMessages = document.querySelectorAll('.text-danger.small');
        for(var i = 0; i < errorMessages.length; i++) {
            errorMessages[i].remove();
        }
    }

    function updateFileNameModal() {
        var input = document.getElementById('fileInputModal');
        var textElement = document.getElementById('file-input-text-modal');
        if (input.files.length > 0) {
            textElement.textContent = input.files[0].name;
        } else {
            textElement.textContent = 'Klik di sini untuk memilih file';
        }
    }
    
    // === SCRIPT BARU UNTUK FULLSCREEN IFRAME ===
    function openFullscreen() {
        var elem = document.getElementById("preview-iframe");
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    }
    
    window.onclick = function(event) {
        if (event.target == document.getElementById("uploadModal")) {
            closeUploadModal();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any() || session('error'))
            @php session()->flash('error_modal_open', true); @endphp
            openUploadModal();
        @endif
    });

</script>

</body>
</html>