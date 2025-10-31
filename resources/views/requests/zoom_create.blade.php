<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Form Request Link Zoom - PUSDA Jatim</title>

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
            min-height: 100vh;
            position: relative; 
        }

        /* Container Luar (Transparan) */
        .outer-container {
            max-width: 950px; /* <-- DIKECILKAN */
            width: 90%;
            background: rgba(0, 0, 0, 0.25); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            padding: 15px; /* <-- DIKECILKAN */
            z-index: 1;
        }
        
        .inner-container {
            display: flex;
            border-radius: 15px; 
            overflow: hidden; 
            align-items: center;
            /* min-height: 500px; */ /* <-- DIHAPUS */
        }

        /* Panel Kiri (Ikut Transparan) */
        .left-panel {
            flex: 1.1; /* <-- DIUBAH: Disesuaikan rasionya */
            background: none; 
            padding: 30px; /* <-- DIKECILKAN */
            color: white; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .left-panel .logo {
            max-width: 160px; /* <-- DIKECILKAN */
            margin-bottom: 20px; /* <-- DIKECILKAN */
        }
        .left-panel h2 {
            font-weight: 700;
            font-size: 2.2rem; /* <-- DIKECILKAN */
            margin-bottom: 20px; /* <-- DIKECILKAN */
        }
        .left-panel .illustration {
            max-width: 90%;
        }
        
        /* Panel Kanan (Form Putih) */
        .right-panel {
            flex: 1.2; /* <-- DIUBAH: Disesuaikan rasionya */
            background: #fff; 
            padding: 25px; /* <-- DIKECILKAN */
            overflow-y: auto; 
            max-height: 80vh; /* Tetap ada max-height untuk jaga-jaga */
            border-radius: 15px;
        }
        .right-panel h3 {
            text-align: left; 
            font-weight: 600;
            color: #333;
            font-size: 1.25rem; /* <-- DIKECILKAN */
            margin-bottom: 20px; /* <-- DIKECILKAN */
        }

        /* Styling Form (Rapi) */
        .form-group {
            position: relative;
            margin-bottom: 0.75rem; /* <-- DIKECILKAN */
        }
        .form-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: #888;
            z-index: 2;
        }
        .form-control {
            background: #f4f7f6; 
            border: none;
            border-radius: 8px;
            height: 42px; /* <-- DIKECILKAN */
            padding-left: 45px; 
            font-size: 0.85rem; /* <-- DIKECILKAN */
        }
        .form-control::placeholder {
            color: #888;
            opacity: 1;
        }
        .form-control:focus {
            background: #fff;
            border: 1px solid #007bff;
            box-shadow: none;
        }
        .form-control.textarea-icon {
             height: auto; 
             padding-top: 12px;
        }

        /* Tombol */
        .button-group {
            display: flex;
            justify-content: space-between; 
            gap: 10px;
            margin-top: 25px; /* <-- DIKECILKAN */
        }
        .btn-custom {
            padding: 10px 20px; /* <-- DIKECILKAN */
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem; /* <-- DIKECILKAN */
        }
        .btn-custom-secondary {
            background-color: #e9ecef;
            color: #333;
        }
        .btn-custom-primary {
            background-color: #007bff;
            color: white;
        }

    </style>
</head>
<body>

    <div class="outer-container">
        <div class="inner-container">

            <div class="left-panel">
                <img src="{{ asset('images/logo.png') }}" alt="Logo PUSDA" class="logo">
                <h2>Selamat Datang</h2>
                <img src="{{ asset('images/meet.png') }}" alt="Ilustrasi Zoom" class="illustration">
            </div>

            <div class="right-panel">
                <h3>Form Request Link Zoom</h3>

                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('request.zoom.store') }}" method="POST"> 
                    {{ csrf_field() }}
                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" class="form-control" id="nama_pemohon" name="nama_pemohon" value="{{ old('nama_pemohon') }}" placeholder="Nama Pemohon" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-building form-icon"></i>
                        <select class="form-control" id="bidang_id" name="bidang_id" required>
                            <option value="">-- Pilih Bidang --</option>
                            @if(isset($bidang))
                                @foreach($bidang as $id => $nama)
                                    <option value="{{ $id }}" {{ old('bidang_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            @else
                                <option value="1">Contoh Bidang 1</option>
                                <option value="2">Contoh Bidang 2</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-id-card form-icon"></i>
                                <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip') }}" placeholder="NIP (opsional)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-phone form-icon"></i>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Nomor Hp" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-calendar-alt form-icon"></i>
                                <input type="text" class="form-control" id="jadwal_mulai" name="jadwal_mulai" value="{{ old('jadwal_mulai') }}" 
                                       placeholder="mm/dd/yyyy --:--" 
                                       onfocus="(this.type='datetime-local')" 
                                       onblur="(if(!this.value) this.type='text')" 
                                       required>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-calendar-alt form-icon"></i>
                                <input type="text" class="form-control" id="jadwal_selesai" name="jadwal_selesai" value="{{ old('jadwal_selesai') }}"
                                       placeholder="mm/dd/yyyy --:--" 
                                       onfocus="(this.type='datetime-local')" 
                                       onblur="(if(!this.value) this.type='text')">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-video form-icon"></i>
                        <input type="text" class="form-control" id="nama_rapat" name="nama_rapat" value="{{ old('nama_rapat') }}" placeholder="Nama / Topik Rapat" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-file-alt form-icon" style="top: 20px; transform: none;"></i>
                        <textarea class="form-control textarea-icon" id="keterangan" name="keterangan" rows="2" placeholder="Keterangan Rapat (opsional)">{{ old('keterangan') }}</textarea> 
                    </div>

                    <div class="button-group">
                        <a href="{{ route('landing-page') }}" class="btn btn-custom btn-custom-secondary">Kembali</a>
                        <button type="submit" class="btn btn-custom btn-custom-primary">Kirim Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Script untuk trik placeholder pada input datetime-local
        document.addEventListener('DOMContentLoaded', function() {
            var jadwalMulai = document.getElementById('jadwal_mulai');
            if (jadwalMulai.value) {
                jadwalMulai.type = 'datetime-local';
            }
            
            var jadwalSelesai = document.getElementById('jadwal_selesai');
            if (jadwalSelesai.value) {
                jadwalSelesai.type = 'datetime-local';
            }
        });
    </SCript>
</body>
</html>