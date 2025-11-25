<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Form Peminjaman Kendaraan - PUSDA Jatim</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            min-height: 100vh;
            position: relative;
            padding: 20px;
        }

        .outer-container {
            max-width: 1250px;
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            padding: 15px;
        }

        .inner-container {
            display: flex;
            border-radius: 15px;
            overflow: hidden;
            align-items: center;
            flex-wrap: wrap;
        }

        .left-panel {
            flex: 1.1;
            background: none;
            padding: x 30px;
            min-height: 700px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left-panel .logo { max-width: 160px; margin-bottom: 10px; }
        .left-panel h2 { font-weight: 700; font-size: 2.2rem; margin-bottom: 10px; }
        .left-panel .illustration { max-width: 90%; }

        .right-panel {
            flex: 1.2;
            background: #fff;
            padding: 35px;
            min-height: 630px;
            border-radius: 15px;
        }

        .right-panel h3 {
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 1.25rem;
            margin-bottom: 25px;
            border-bottom: 2px solid #f4f7f6;
            padding-bottom: 10px;
        }

        .form-group { position: relative; margin-bottom: 1rem; }
        .form-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: #888;
            z-index: 2;
        }
        .form-group label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
            display: block;
            font-weight: 500;
        }

        /* CSS untuk Select agar Icon pas */
        select.form-control {
            appearance: none; /* Hilangkan panah default browser agar rapi */
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        .form-control {
            background: #f4f7f6;
            border: 1px solid transparent;
            border-radius: 8px;
            height: 45px;
            padding-left: 45px; /* Ruang untuk icon di kiri */
            padding-right: 30px;
            font-size: 0.9rem;
            transition: all 0.3s;
            width: 100%;
        }
        .form-control:focus {
            background: #fff;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
        }

        /* === STYLING TOMBOL SESUAI GAMBAR === */
        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .btn-custom {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            min-width: 120px;
            text-align: center;
        }

        /* Tombol KEMBALI (Abu-abu Muda) */
        .btn-back {
            background-color: #e9ecef;
            color: #333;
        }
        .btn-back:hover {
            background-color: #d3d6d8;
            color: #000;
            text-decoration: none;
        }

        /* Tombol KIRIM REQUEST (Biru Terang) */
        .btn-submit {
            background-color: #007bff;
            color: white;
        }
        .btn-submit:hover {
            background-color: #0056b3;
            color: white;
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            body {
                display: block;
                height: auto;
                padding: 10px;
            }
            .outer-container {
                margin-top: 20px;
                background: rgba(0, 0, 0, 0.4);
            }
            .inner-container { flex-direction: column; }
            
            .left-panel { order: 1; padding: 20px; }
            .left-panel .illustration { display: none; }
            .left-panel h2 { font-size: 1.5rem; }
            .left-panel .logo { max-width: 100px; }

            .right-panel { 
                order: 2; 
                width: 100%; 
                padding: 20px;
                border-radius: 15px;
            }
            
            .button-group {
                flex-direction: column-reverse;
                gap: 10px;
            }
            .btn-custom {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="outer-container">
        <div class="inner-container">
            <div class="left-panel">
                <img src="{{ asset('images/logo.png') }}" alt="Logo PUSDA" class="logo">
                <h2>Peminjaman Kendaraan</h2>
                <p>Silakan isi form di samping untuk mengajukan peminjaman kendaraan dinas.</p>
                <img src="{{ asset('images/mobil.png') }}" alt="Ilustrasi" class="illustration">
            </div>

            <div class="right-panel">
                <h3>Form Peminjaman</h3>

                @if (session('success'))
                    <div class="alert alert-success fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger fade show" role="alert">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('request.kendaraan.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" class="form-control" name="nama" placeholder="Nama Pemohon" value="{{ old('nama') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-id-badge form-icon"></i>
                                <input type="text" class="form-control" name="nip" placeholder="NIP" value="{{ old('nip') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-phone form-icon"></i>
                                <input type="text" class="form-control" name="no_hp" placeholder="Nomor HP / WA" value="{{ old('no_hp') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-exclamation-circle form-icon"></i>
                        <input type="text" class="form-control" name="urgensi" placeholder="Urgensi / Keperluan Peminjaman" value="{{ old('urgensi') }}" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-car form-icon"></i>
                        <select class="form-control" name="kendaraan_id" id="kendaraan_id" required>
                            <option value="" selected disabled>-- Pilih Kendaraan --</option>
                            @forelse($kendaraans as $k)
                                <option value="{{ $k->id }}" {{ old('kendaraan_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->jenis }} - {{ $k->plat_no }} - {{ $k->status }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada kendaraan tersedia</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal & Jam Ambil</label>
                                <i class="fas fa-calendar-check form-icon" style="top: 38px;"></i>
                                <input type="datetime-local" class="form-control" name="tanggal_ambil" 
                                       value="{{ old('tanggal_ambil') ? \Carbon\Carbon::parse(old('tanggal_ambil'))->format('Y-m-d\TH:i') : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal & Jam Kembali</label>
                                <i class="fas fa-calendar-times form-icon" style="top: 38px;"></i>
                                <input type="datetime-local" class="form-control" name="tanggal_kembali" 
                                       value="{{ old('tanggal_kembali') ? \Carbon\Carbon::parse(old('tanggal_kembali'))->format('Y-m-d\TH:i') : '' }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="button-group">
                        <a href="{{ route('landing-page') }}" class="btn-custom btn-back">
                            Kembali
                        </a>
                        <button type="submit" class="btn-custom btn-submit">
                            Kirim Request
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>