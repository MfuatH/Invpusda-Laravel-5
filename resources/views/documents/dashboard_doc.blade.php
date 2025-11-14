<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen Rapat</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #F4F6F9;
        }

        /* HEADER */
        .header {
            width: 100%;
            background: #fff;
            padding: 12px 30px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header img {
            width: 170px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Section Title */
        .section-title {
            font-size: 28px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1F78D1;
            margin-bottom: 20px;
        }

        /* CARD */
        .card {
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .card h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 18px;
            color: #333;
        }

        .btn-primary {
            background: #2E8AE1;
            color: #fff;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #236fb4;
        }

        .card-grey {
            background: #EEF1F5;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
        }

        .desc {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .desc b {
            color: #333;
        }
        .desc ul {
            padding-left: 20px;
            margin-top: 5px;
        }

        .sample-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .sample-card h4 {
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .pdf-icon {
            width: 65px;
            margin: 0 15px;
            cursor: pointer;
            transition: 0.2s;
        }

        .pdf-icon:hover {
            transform: scale(1.06);
            opacity: 0.85;
        }

        /* Tombol submit di dalam modal */
        .btn-submit {
            background: #1F78D1;
            color: #fff;
            padding: 10px 18px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: #155fa3;
        }

        /* MODAL (UMUM) */
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
            z-index: 999;
        }

        .modal-box {
            width: 80%;
            max-width: 500px;
            height: auto;
            max-height: 85vh;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            background: #1F78D1;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header .title {
            font-weight: 600;
        }

        .modal-header button, .modal-close-btn {
            background: white;
            color: #1F78D1;
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }
        
        .modal-body {
            padding: 20px 25px;
            overflow-y: auto;
        }

        /* Khusus Modal Preview PDF */
        .modal-box-pdf {
            width: 80%;
            height: 85%;
            max-width: none;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        /* == STYLE FORM UNTUK MODAL == */
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
        }
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .form-group .opsional {
            font-weight: 400;
            color: #777;
            font-size: 14px;
        }

        /* TAMPILAN BARU UNTUK FILE INPUT */
        .file-input-wrapper {
            position: relative;
            width: 100%;
            text-align: center;
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 30px;
            box-sizing: border-box;
            background: #fafafa;
            cursor: pointer;
            transition: .2s;
        }
        .file-input-wrapper:hover {
            background: #f4f4f4;
            border-color: #1F78D1;
        }
        .file-input-wrapper .file-input-icon {
            font-size: 32px;
            color: #1F78D1;
        }
        .file-input-wrapper .file-input-text {
            font-size: 16px;
            color: #555;
            font-weight: 500;
            margin-top: 10px;
        }
        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        
        .file-name {
            margin-top: 15px;
            font-size: 15px;
            color: #1F78D1;
            font-weight: 600;
            text-align: center;
        }
        
    </style>
</head>

<body>

    <div class="header">
        <img src="/images/logo.png" alt="Logo">
    </div>

    <div class="container">
        
        <h2 class="section-title">
            <i class="fas fa-file-upload"></i>
            Upload Dokumen Rapat
        </h2>

        <div class="card">
            <h3>Unggah Dokumen</h3>

            <button type="button" class="btn-primary" onclick="openUploadModal()">
                <i class="fas fa-plus"></i>
                Upload Dokumen
            </button>

            <div class="card-grey">
                
                <div class="desc">
                    Dokumen harus PDF atau Foto (jpg/png).
                    <br><br>
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
                </div>

                <div class="sample-card">
                    <h4>Contoh Dokumen (Preview di halaman ini)</h4>

                    <img src="/images/pdf.png" class="pdf-icon" onclick="previewPDF('/sample/presensi.pdf')">
                    <img src="/images/pdf.png" class="pdf-icon" onclick="previewPDF('/sample/notulen.pdf')">
                    <img src="/images/pdf.png" class="pdf-icon" onclick="previewPDF('/sample/nodin.pdf')">
                </div>
            </div>

        </div>
    </div>

    <div class="modal-bg" id="uploadModal">
        <div class="modal-box">
            <div class="modal-header">
                <span class="title">Form Upload Laporan</span>
                <button class="modal-close-btn" onclick="closeUploadModal()">Tutup</button>
            </div>
            
            <div class="modal-body">
                
                <form action="/upload-dokumen" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="pengunggah">Pengunggah</label>
                        <input type="text" id="pengunggah" name="pengunggah" required>
                    </div>

                    <div class="form-group">
                        <label for="nip">NIP <span class="opsional">(Opsional)</span></label>
                        <input type="text" id="nip" name="nip">
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan <span class="opsional">(Opsional)</span></label>
                        <textarea id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fileInput" style="margin-bottom: 10px;">File <span style="color:red;">(Wajib)</span></label>
                        
                        <label class="file-input-wrapper" for="fileInput">
                            <div class="file-input-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <div class="file-input-text">Klik di sini untuk memilih file</div>
                            <input type="file" id="fileInput" name="dokumen" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName()" required>
                        </label>
                        
                        <div id="fileName" class="file-name"></div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Upload Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>


    <div class="modal-bg" id="previewModal">
        <div class="modal-box modal-box-pdf">
            <div class="modal-header">
                <span class="title">Preview Dokumen</span>
                <div>
                    <button onclick="closePreviewModal()">Tutup</button>
                </div>
            </div>
            <iframe id="pdfFrame" src=""></iframe>
        </div>
    </div>

    <script>
        function showFileName() {
            let fileInput = document.getElementById("fileInput");
            let fileNameDisplay = document.getElementById("fileName");
            
            if (fileInput.files.length > 0) {
                let file = fileInput.files[0];
                fileNameDisplay.innerHTML = "📎 File dipilih: <b>" + file.name + "</b>";
            } else {
                fileNameDisplay.innerHTML = "";
            }
        }

        // === FUNGSI MODAL BARU (UPLOAD FORM) ===
        function openUploadModal() {
            document.getElementById("uploadModal").style.display = "flex";
        }
        function closeUploadModal() {
            document.getElementById("uploadModal").style.display = "none";
            document.getElementById("fileInput").value = null;
            document.getElementById("fileName").innerHTML = "";
        }


        // === FUNGSI MODAL LAMA (PREVIEW PDF) ===
        function previewPDF(path) {
            document.getElementById("pdfFrame").src = path;
            /* ==============================
                PERUBAHAN DI SINI:
                Baris 'downloadBtn' telah dihapus
                ==============================
            */
            document.getElementById("previewModal").style.display = "flex";
        }
        function closePreviewModal() {
            document.getElementById("previewModal").style.display = "none";
            document.getElementById("pdfFrame").src = "";
        }
    </script>

</body>
</html>