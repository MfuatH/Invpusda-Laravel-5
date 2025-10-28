<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Form Permintaan Barang - PUSDA Jatim</title>

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
            /* min-height: 500px; */ /* <-- DIHAPUS agar tidak terlalu tinggi */
        }

        .left-panel {
            flex: 1.1; 
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
            flex: 1.2; 
            background: #fff; 
            padding: 25px; /* <-- DIKECILKAN */
        }
        .right-panel h3 {
            text-align: left; 
            font-weight: 600;
            color: #333;
            font-size: 1.25rem; /* <-- DIKECILKAN */
            margin-bottom: 20px; /* <-- DIKECILKAN */
        }

        /* Styling Form */
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

        /* Styling untuk grup barang yang diminta */
        .item-request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px; /* <-- DIKECILKAN */
            margin-top: 20px; /* <-- DIKECILKAN */
        }
        .item-request-header h4 {
            font-size: 0.9rem; /* <-- DIKECILKAN */
            font-weight: 600;
            margin-bottom: 0;
            color: #555;
        }
        .item-row {
            display: flex;
            gap: 10px;
            margin-bottom: 0.75rem; /* <-- DIKECILKAN */
            align-items: center;
        }
        .item-row .form-control {
            flex: 1;
            padding-left: 15px; 
        }
        .item-row .form-control[type="number"] {
             flex: 0.5; 
             text-align: center;
             padding-left: 5px;
             padding-right: 5px;
        }
        .btn-add-item, .btn-remove-item {
            width: 42px; /* <-- DIKECILKAN */
            height: 42px; /* <-- DIKECILKAN */
            border-radius: 8px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem; /* <-- DIKECILKAN */
        }
        .btn-add-item {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-remove-item {
            background-color: #dc3545; 
            color: white;
            border: none;
        }


        /* Tombol Footer */
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
                <img src="{{ asset('images/ils.png') }}" alt="Ilustrasi Barang" class="illustration">
            </div>

            <div class="right-panel">
                <h3>Form Request Barang</h3>

                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('request.barang.store') }}" method="POST">

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
                    
                    
                    <div class="item-request-header">
                        <h4>Barang yang diminta</h4>
                        <button type="button" class="btn btn-sm btn-add-item"><i class="fas fa-plus"></i></button>
                    </div>

                    <div id="item-list">
                        <div class="item-row">
                            <select class="form-control" name="items[0][item_id]" required>
                                <option value="">-- Pilih Barang --</option>
                                @if(isset($items))
                                    @foreach($items as $id => $nama_barang)
                                        <option value="{{ $id }}">{{ $nama_barang }}</option>
                                    @endforeach
                                @else
                                    <option value="1">Contoh Barang A</option>
                                    <option value="2">Contoh Barang B</option>
                                @endif
                            </select>
                            <input type="number" class="form-control" name="items[0][jumlah_request]" min="1" value="1" placeholder="Jumlah" required>
                            <button type="button" class="btn btn-remove-item" style="display:none;"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <a href="{{ route('landing-page') }}" class="btn btn-custom btn-custom-secondary">Kembali</a>
                        <button type="submit" class="btn btn-custom btn-custom-primary">Kirim Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var itemIndex = 0; 

            $('.btn-add-item').click(function() {
                itemIndex++;
                var newItemRow = `
                    <div class="item-row">
                        <select class="form-control" name="items[${itemIndex}][item_id]" required>
                            <option value="">-- Pilih Barang --</option>
                            @if(isset($items))
                                @foreach($items as $id => $nama_barang)
                                    <option value="{{ $id }}">{{ $nama_barang }}</option>
                                @endforeach
                            @else
                                <option value="1">Contoh Barang A</option>
                                <option value="2">Contoh Barang B</option>
                            @endif
                        </select>
                        <input type="number" class="form-control" name="items[${itemIndex}][jumlah_request]" min="1" value="1" placeholder="Jumlah" required>
                        <button type="button" class="btn btn-remove-item"><i class="fas fa-minus"></i></button>
                    </div>
                `;
                $('#item-list').append(newItemRow);

                if ($('.item-row').length > 1) {
                    $('.item-row .btn-remove-item').show();
                }
            });

            $(document).on('click', '.btn-remove-item', function() {
                $(this).closest('.item-row').remove();
                
                if ($('.item-row').length === 1) {
                    $('.item-row .btn-remove-item').hide();
                }

                $('#item-list .item-row').each(function(index) {
                    $(this).find('select').attr('name', `items[${index}][item_id]`);
                    $(this).find('input[type="number"]').attr('name', `items[${index}][jumlah_request]`);
                });
                itemIndex = $('.item-row').length - 1; 
            });

            if ($('.item-row').length === 1) {
                $('.item-row .btn-remove-item').hide();
            }
        });
    </script>
</body>
</html>