<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen Rapat</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            gap: 10px;
            color: #1F78D1;
            margin-bottom: 20px;
        }

        .section-title:before {
            content: "📄";
            font-size: 30px;
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
        }

        .btn-primary:hover {
            background: #236fb4;
        }

        #fileInput {
            display: none;
        }

        .file-name {
            margin-top: 12px;
            font-size: 15px;
            color: #444;
            font-weight: 500;
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

        /* UPDATE: tombol kecil kiri bawah */
        .btn-submit {
            background: #1F78D1;
            color: #fff;
            padding: 10px 18px;
            width: auto;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: #155fa3;
        }

        /* MODAL PREVIEW */
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
            height: 85%;
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

        .modal-header button {
            background: white;
            color: #1F78D1;
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <img src="/images/logo.png" alt="Logo">
    </div>

    <div class="container">

        <h2 class="section-title">Upload Dokumen Rapat</h2>

        <div class="card">
            <h3>Unggah Dokumen</h3>

            <form action="/upload-dokumen" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <button type="button" class="btn-primary" onclick="document.getElementById('fileInput').click();">
                    Pilih File Dokumen
                </button>

                <input type="file" id="fileInput" name="dokumen" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName()">

                <div id="fileName" class="file-name"></div>

                <div class="card-grey">

                    <div class="desc">
                        Dokumen harus PDF atau Foto (jpg/png).
                        <br><br><b>Urutan dokumen:</b><br>
                        • Presensi<br>
                        • Notulen<br>
                        • Nodin<br>
                        • Lampiran pendukung
                    </div>

                    <!-- CONTOH DOKUMEN -->
                    <div class="sample-card">
                        <h4>Contoh Dokumen (Preview di halaman ini)</h4>

                        <img src="/images/pdf.png" class="pdf-icon" onclick="previewPDF('/sample/presensi.pdf')">
                        <img src="/images/pdf.png" class="pdf-icon" onclick="previewPDF('/sample/notulen.pdf')">
                        <img src="/images/pdf.png" class="pdf-icon" onclick="previewPDF('/sample/nodin.pdf')">
                    </div>

                    <!-- UPDATE: TOMBOL KECIL KIRI BAWAH -->
                    <div style="margin-top: 20px; display: flex; justify-content: flex-start;">
                        <button type="submit" class="btn-submit">Kirim Dokumen</button>
                    </div>

                </div>

            </form>
        </div>

    </div>

    <!-- MODAL PREVIEW -->
    <div class="modal-bg" id="previewModal">
        <div class="modal-box">
            <div class="modal-header">
                <span>Preview Dokumen</span>
                <div>
                    <a id="downloadBtn" href="" download>
                        <button>Download</button>
                    </a>
                    <button onclick="closeModal()">Tutup</button>
                </div>
            </div>
            <iframe id="pdfFrame" src=""></iframe>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function showFileName() {
            let file = document.getElementById("fileInput").files[0];
            if (file) {
                document.getElementById("fileName").innerHTML =
                    "📎 File dipilih: <b>" + file.name + "</b>";
            }
        }

        // OPEN PREVIEW
        function previewPDF(path) {
            document.getElementById("pdfFrame").src = path;
            document.getElementById("downloadBtn").href = path;
            document.getElementById("previewModal").style.display = "flex";
        }

        // CLOSE PREVIEW
        function closeModal() {
            document.getElementById("previewModal").style.display = "none";
            document.getElementById("pdfFrame").src = "";
        }
    </script>

</body>

</html>
