<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - PUSDA Jatim</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', Arial, sans-serif;
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
        }

        .logo {
            max-width: 200px;
            margin-bottom: 25px;
        }

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

        .login-card {
            width: 400px; 
            padding: 35px; 
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 25px; 
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            color: white;
        }

        .login-card h2 {
            text-align: center;
            font-weight: 700; 
            margin-bottom: 30px;
            color: #f9f9f9;
            font-size: 1.8rem;
        }

        .login-card .form-group {
            margin-bottom: 1.25rem;
        }

        .login-card label {
            font-size: 0.9rem;
            color: #FFFFFF; 
            font-weight: 500;
        }

        /* ========================================
           PERUBAHAN CSS DIMULAI DARI SINI
           ========================================
        */

        /* 1. INPUT TETAP TRANSPARAN, TEKS PUTIH */
        .login-card .form-control {
            background: rgba(255, 255, 255, 0.2); /* Transparan */
            border: 1px solid rgba(255, 255, 255, 0.4); 
            border-radius: 8px;
            padding: 12px 15px;
            color: #FFFFFF; /* Teks yang diketik jadi PUTIH */
            height: auto;
            transition: border-color 0.3s ease; /* Transisi untuk focus */
        }
        
        /* 2. STYLE UNTUK PLACEHOLDER (KOSONG) */
        .login-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
            opacity: 1; 
        }
        
        /* 3. HAPUS BLOK YANG MEMBUAT JADI PUTIH */
        /* .login-card .form-control:focus,
           .login-card .form-control:not(:placeholder-shown) {
             ... (BLOK INI DIHAPUS) ...
           }
        */

        /* 4. TAMBAHKAN STYLE :focus (Hanya border) */
        .login-card .form-control:focus {
            background: rgba(255, 255, 255, 0.2); /* Pastikan tetap transparan */
            color: #FFFFFF; /* Pastikan teks tetap putih */
            border-color: rgba(255, 255, 255, 0.8); /* Border lebih terang saat di-klik */
            box-shadow: none; /* Hapus glow default */
        }

        /* 5. STYLE IKON MATA (TETAP TERLIHAT) */
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .form-control {
            padding-right: 40px; 
        }
        .password-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7); /* <-- DIUBAH: Dibuat terang */
            cursor: pointer;
        }
        
        /* 6. HAPUS BLOK :focus UNTUK IKON */
        /* .login-card .form-control:focus + .toggle-password, ... {
             ... (BLOK INI DIHAPUS) ...
           }
        */

        /* 7. STYLE BARU UNTUK ERROR MESSAGE (MERAH) */
        .invalid-feedback-custom {
            display: block;
            width: 100%;
            margin-top: .25rem;
            font-size: 80%;
            color: #ff6b6b; /* Warna merah muda terang */
            font-weight: 500;
        }

        /* ========================================
           PERUBAHAN CSS SELESAI
           ========================================
        */

        .login-card .form-check {
            display: flex;
            align-items: center;
        }
        .login-card .form-check-label {
            font-size: 0.9rem;
            color: #f0f0f0; 
            font-weight: 400;
            padding-left: 5px;
        }
        .login-card .form-check-input {
             margin-top: 0; 
        }
        .btn-login {
            background: #FFFFFF; 
            color: #2a3a57; 
            font-weight: 700; 
            border: none;
            border-radius: 50px; 
            padding: 12px;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px; 
        }
        .btn-login:hover {
            background: #f0f0f0;
            color: #2a3a57;
            transform: scale(1.02); 
        }
        .login-card hr {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 25px;
            margin-bottom: 20px;
        }
        .login-card .footer-text {
            text-align: center;
            color: rgba(255, 255, 255, 0.7); 
            font-size: 0.8rem;
            font-weight: 300;
        }

    </style>
</head>
<body>

    <a id="change-bg-btn" class="change-bg-button" title="Ganti Latar Belakang">
        <i class="fas fa-images"></i>
    </a>

    <img src="{{ asset('images/logo.png') }}" alt="Logo PUSDA" class="logo">

    <div class="login-card">
        <h2>Selamat Datang</h2>

        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="" required autofocus>
                
                @if ($errors->has('email'))
                    <span class="invalid-feedback-custom" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
                
                @if ($errors->has('password'))
                    <span class="invalid-feedback-custom" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            <div class="form-group mb-0">
                <button type="submit" class="btn btn-login">
                    LOG IN
                </button>
            </div>
            
            <hr>
            <div class="footer-text">
                Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur
            </div>

        </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        
        // Logika Ganti Background
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

        // Logika Toggle Password
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function (e) {
                const password = document.getElementById('password');
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
    });
    </script>
</body>
</html>