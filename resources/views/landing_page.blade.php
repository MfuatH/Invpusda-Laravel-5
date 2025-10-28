<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Selamat Datang - PUSDA Jatim</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet"> 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', Arial, sans-serif;
            overflow: hidden; 
        }
        body {
            background-image: url('{{ asset('images/background.jpeg') }}'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
            display: flex;
            align-items: center; 
            justify-content: center; 
            flex-direction: column;
            min-height: 100vh;
            
            transition: background-image 0.5s ease-in-out;
            position: relative; 
        }

        /* Tombol Ganti Background */
        .change-bg-button {
            position: absolute;
            top: 25px;
            right: 25px;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.3s ease;
            z-index: 99;
        }
        .change-bg-button:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Logo PUSDA */
        .logo {
            max-width: 250px; 
            margin-bottom: 30px; 
            position: relative;
            z-index: 1; 
        }

        /* TULISAN "SELAMAT DATANG" */
        .welcome-title {
            font-family: 'Dancing Script', cursive; 
            font-weight: 700; 
            color: #f9f9f9;
            font-size: 2.5rem; /* <-- DIUBAH: Dikecilkan dari 4rem */
            text-shadow: 0 4px 8px rgba(0,0,0,0.4); 
            margin-bottom: 30px; /* <-- DIUBAH: Jarak ke tombol dikurangi */
            text-align: center;
            line-height: 1; 
            position: relative;
            z-index: 1;
        }

        .action-button-wrapper {
            display: flex;
            justify-content: center;
            gap: 25px; 
            position: relative;
            z-index: 1;
        }

        /* KEDUA TOMBOL CONTAINER */
        .action-button {
            background: rgba(0, 0, 0, 0.35); 
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 25px; /* <-- DIUBAH: Lebih bulat */
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);

            padding: 20px;
            color: white;
            font-size: 0.9rem; /* <-- DIUBAH: Teks lebih kecil */
            font-weight: 500;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            
            width: 250px; /* <-- DIUBAH: Lebih lebar */
            height: 150px; /* <-- DIUBAH: Lebih tinggi */
            text-align: center; 
        }
        
        .action-button:hover {
            background: rgba(0, 0, 0, 0.5);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
            color: white;
            text-decoration: none;
        }
        
        /* IKON DI DALAM TOMBOL */
        .action-button i {
            font-size: 3.5rem; /* <-- DIUBAH: Ikon lebih besar */
            margin-bottom: 15px; /* <-- DIUBAH: Jarak ke teks ditambah */
            color: #FFFFFF;
        }

        .alert {
            margin-top: 20px;
            background: rgba(40, 167, 69, 0.8);
            border: none;
            color: white;
            position: relative;
            z-index: 1;
        }

    </style>
</head>
<body>

    <a id="change-bg-btn" class="change-bg-button" title="Ganti Latar Belakang">
        <i class="fas fa-images"></i> 
    </a>

    <img src="{{ asset('images/logo.png') }}" alt="Logo PUSDA" class="logo">

    <h2 class="welcome-title">Selamat Datang</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="action-button-wrapper">
        <a class="action-button" href="{{ route('request.barang.create') }}" role="button">
            <i class="fas fa-clipboard-list"></i> 
            Permintaan Barang
        </a>
        <a class="action-button" href="{{ route('request.zoom.create') }}" role="button">
            <i class="fas fa-video"></i>
            Permintaan Link Zoom
        </a>
    </div>

    @php
        // Script PHP untuk ambil config (tetap sama)
        $themeListFromConfig = config('themes.list', []);
        $backgroundPaths = [];
        foreach ($themeListFromConfig as $theme) {
            $backgroundPaths[] = asset($theme['path']);
        }
    @endphp

    <script>
    // Script JS (tetap sama)
    document.addEventListener('DOMContentLoaded', function() {
        const backgroundImages = @json($backgroundPaths);
        let currentBgIndex = 0;
        const changeBgBtn = document.getElementById('change-bg-btn');
        const bodyElement = document.body;

        if (changeBgBtn && backgroundImages.length > 0) {
            bodyElement.style.backgroundImage = `url('${backgroundImages[currentBgIndex]}')`;
            changeBgBtn.addEventListener('click', function () {
                currentBgIndex = (currentBgIndex + 1) % backgroundImages.length;
                bodyElement.style.backgroundImage = `url('${backgroundImages[currentBgIndex]}')`;
            });
        }
    });
    </script>
</body>
</html>