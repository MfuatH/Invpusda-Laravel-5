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
      overflow-x: hidden;
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
      padding: 20px;
    }

    /* Tombol Ganti Background */
    .change-bg-button {
      position: absolute;
      top: 20px;
      right: 20px;
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

    /* Logo */
    .logo {
      max-width: 250px;
      margin-bottom: 25px;
      z-index: 1;
    }

    /* Judul */
    .welcome-title {
      font-family: 'Dancing Script', cursive;
      font-weight: 700;
      color: #fff;
      font-size: 2.5rem;
      text-shadow: 0 4px 8px rgba(0,0,0,0.4);
      margin-bottom: 30px;
      text-align: center;
    }

    /* Grid menu */
    .action-button-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      max-width: 1000px;
      width: 100%;
      z-index: 1;
    }

    .button-row {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      width: 100%;
    }

    .action-button {
      background: rgba(0, 0, 0, 0.35);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 25px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      padding: 20px;
      color: white;
      text-align: center;
      font-size: 0.9rem;
      font-weight: 500;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 230px;
      height: 150px;
      transition: all 0.3s ease;
    }

    .action-button:hover {
      background: rgba(0, 0, 0, 0.5);
      border-color: rgba(255, 255, 255, 0.3);
      transform: translateY(-5px);
      color: white;
      text-decoration: none;
    }

    .action-button i {
      font-size: 3rem;
      margin-bottom: 15px;
    }

    .alert {
      margin-top: 20px;
      background: rgba(40, 167, 69, 0.8);
      border: none;
      color: white;
      position: relative;
      z-index: 1;
    }

    /* Responsif */
    @media (max-width: 768px) {
      .logo {
        max-width: 180px;
      }
      .welcome-title {
        font-size: 2rem;
      }
      .action-button {
        width: 160px;
        height: 130px;
        font-size: 0.8rem;
      }
      .action-button i {
        font-size: 2.5rem;
      }
    }

    @media (max-width: 480px) {
      .button-row {
        justify-content: center;
      }
      .action-button {
        width: 45%;
        height: 120px;
      }
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
    <!-- Baris pertama -->
    <div class="button-row">
      <a class="action-button" href="{{ route('request.barang.create') }}">
        <i class="fas fa-clipboard-list"></i> Permintaan Barang
      </a>
      <a class="action-button" href="{{ route('request.zoom.create') }}">
        <i class="fas fa-video"></i> Permintaan Link Zoom
      </a>
      <a class="action-button">
        <i class="fas fa-utensils"></i> Makanan & Minuman
      </a>
      <a class="action-button">
        <i class="fas fa-file-upload"></i> Upload Nota Dinas / Undangan
      </a>
    </div>

    <!-- Baris kedua -->
    <div class="button-row" style="justify-content: center;">
      <a class="action-button">
        <i class="fas fa-download"></i> Download Presensi
      </a>
      <a class="action-button">
        <i class="fas fa-file-download"></i> Download Notulen
      </a>
      <a class="action-button">
        <i class="fas fa-cloud-upload-alt"></i> Upload Presensi, Notulensi & Dokumentasi
      </a>
    </div>
  </div>

  @php
    $themeListFromConfig = config('themes.list', []);
    $backgroundPaths = [];
    foreach ($themeListFromConfig as $theme) {
        $backgroundPaths[] = asset($theme['path']);
    }
  @endphp

  <script>
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
