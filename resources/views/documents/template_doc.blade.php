<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Catering Berhasil</title>

    {{-- Bootstrap & Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
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
            max-width: 1140px;
            width: 100%;
            background: rgba(0,0,0,0.25);
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
            min-height: 700px;
            display: flex;
            flex-direction: column;
        }
        .left-panel img.logo {
            max-width: 150px;
            margin-bottom: 20px;
            align-self: center;
        }
        .right-panel {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            min-height: 700px;
            overflow-y: auto;
            max-height: 85vh;
        }
        .card-grey {
            background: #EEF1F5;
            padding: 25px;
            border-radius: 12px;
        }
        .btn-download {
            background: #0d6efd;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: .2s;
        }
        .btn-download:hover {
            background: #0b5ed7;
        }
        .doc-list li {
            margin-bottom: 12px;
            font-size: 16px;
        }
        .doc-item {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #dce1e7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .doc-item i {
            color: #0d6efd;
        }
    </style>
</head>

<body>

<div class="outer-container">
    <div class="row g-0">

        {{-- PANEL KIRI --}}
        <div class="col-lg-5 left-panel">
            <img src="/images/logo.png" class="logo" alt="Logo">

            <h3 class="mt-2 mb-3"></h3>
            <img src="/images/successgif.gif" alt="">
            
        </div>

        {{-- PANEL KANAN --}}
        <div class="col-lg-7 p-3">
            <div class="right-panel">

                @if(session('success'))
                    <div class="alert alert-success fw-bold text-center">{{ session('success') }}</div>
                @endif

                <div class="card-grey">
                    <h2 class="fw-bold text-center mb-3">🎉 Permintaan Catering Berhasil!</h2>

                    <p class="desc">
                        Terima kasih, permintaan konsumsi Anda telah berhasil dikirim ke sistem.
                        Anda dapat mengunduh dokumen berikut untuk kelengkapan administrasi:
                    </p>

                    <ul class="doc-list list-unstyled mt-4">

                        {{-- PRESENSI --}}
                        <li>
                            <div class="doc-item">
                                <div><i class="fas fa-file-pdf"></i> &nbsp; Template Presensi</div>
                                <a href="{{ asset('sample/Presensi.pdf') }}" download class="btn-download">
                                     Download
                                </a>
                            </div>
                        </li>

                        {{-- NOTULEN --}}
                        <li>
                            <div class="doc-item">
                                <div><i class="fas fa-file-pdf"></i> &nbsp; Template Notulen Rapat</div>
                                <a href="{{ asset('sample/Template_NOTULA.pdf') }}" download class="btn-download">
                                     Download
                                </a>
                            </div>
                        </li>

                    </ul>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('request.konsumsi.create') }}" class="btn btn-secondary">
                        Kembali ke Form Catering
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function openFullscreen() {
        var elem = document.querySelector("iframe");
        if (elem.requestFullscreen) elem.requestFullscreen();
        else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
        else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
    }
</script>

</body>
</html>
