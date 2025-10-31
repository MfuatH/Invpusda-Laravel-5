<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Permintaan Barang - PUSDA Jatim</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            max-width: 950px;
            width: 90%;
            background: rgba(0, 0, 0, 0.25); 
            backdrop-filter: blur(12px);
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
        }
        .left-panel {
            flex: 1.1; 
            background: none; 
            padding: 30px;
            color: white; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .left-panel .logo { max-width: 160px; margin-bottom: 20px; }
        .left-panel h2 { font-weight: 700; font-size: 2.2rem; margin-bottom: 20px; }
        .left-panel .illustration { max-width: 90%; }

        .right-panel {
            flex: 1.2; 
            background: #fff; 
            padding: 25px;
            border-radius: 15px;
        }
        .right-panel h3 {
            text-align: left; 
            font-weight: 600;
            color: #333;
            font-size: 1.25rem;
            margin-bottom: 20px;
        }

        .form-group { position: relative; margin-bottom: 0.75rem; }
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
            height: 42px;
            padding-left: 45px; 
            font-size: 0.85rem;
        }
        .form-control:focus {
            background: #fff;
            border: 1px solid #007bff;
            box-shadow: none;
        }

        .item-request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 10px;
        }
        .item-request-header h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0;
            color: #555;
        }
        .item-row {
            display: flex;
            gap: 10px;
            margin-bottom: 0.75rem;
            align-items: center;
        }
        .item-row select.form-control {
            flex: 1;
            padding-left: 15px; 
        }
        .item-row input[type="number"] {
            width: 90px;
            text-align: center;
        }
        .satuan-label {
            min-width: 60px;
            text-align: left;
            font-size: 0.85rem;
            color: #555;
        }
        .btn-add-item, .btn-remove-item {
            width: 42px; height: 42px; border-radius: 8px;
            display: flex; justify-content: center; align-items: center;
            font-size: 1rem; border: none; font-weight: bold;
        }
        .btn-add-item { background-color: #007bff; color: white; }
        .btn-remove-item { background-color: #dc3545; color: white; }

        .button-group {
            display: flex;
            justify-content: space-between; 
            gap: 10px;
            margin-top: 25px;
        }
        .btn-custom {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-custom-secondary { background-color: #e9ecef; color: #333; }
        .btn-custom-primary { background-color: #007bff; color: white; }
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
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('request.barang.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" class="form-control" name="nama_pemohon" placeholder="Nama Pemohon" value="{{ old('nama_pemohon') }}" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-building form-icon"></i>
                        <select class="form-control" name="bidang_id" required>
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidang ?? [] as $id => $nama)
                                <option value="{{ $id }}" {{ old('bidang_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-id-card form-icon"></i>
                                <input type="text" class="form-control" name="nip" placeholder="NIP (opsional)" value="{{ old('nip') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <i class="fas fa-phone form-icon"></i>
                                <input type="text" class="form-control" name="no_hp" placeholder="Nomor HP" value="{{ old('no_hp') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="item-request-header">
                        <h4>Barang yang diminta</h4>
                        <button type="button" class="btn btn-add-item"><i class="fas fa-plus"></i></button>
                    </div>

                    <div id="item-list">
                        <div class="item-row">
                            <select class="form-control item-select" name="items[0][item_id]" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items ?? [] as $item)
                                    <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}">
                                        {{ $item->nama_barang }} — (Stok: {{ $item->jumlah }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" class="form-control item-jumlah" name="items[0][jumlah_request]" min="1" value="1" required>
                            <span class="satuan-label">-</span>
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
        $(function() {
            let itemIndex = 0;

            // Tambah baris barang
            $('.btn-add-item').click(function() {
                itemIndex++;
                let newRow = `
                    <div class="item-row">
                        <select class="form-control item-select" name="items[${itemIndex}][item_id]" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($items ?? [] as $item)
                                <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}">
                                    {{ $item->nama_barang }} — (Stok: {{ $item->jumlah }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" class="form-control item-jumlah" name="items[${itemIndex}][jumlah_request]" min="1" value="1" required>
                        <span class="satuan-label">-</span>
                        <button type="button" class="btn btn-remove-item"><i class="fas fa-minus"></i></button>
                    </div>
                `;
                $('#item-list').append(newRow);
                updateRemoveButtons();
            });

            // Hapus baris barang
            $(document).on('click', '.btn-remove-item', function() {
                $(this).closest('.item-row').remove();
                updateRemoveButtons();
            });

            // Update label satuan ketika barang dipilih
            $(document).on('change', '.item-select', function() {
                let satuan = $(this).find(':selected').data('satuan') || '-';
                $(this).closest('.item-row').find('.satuan-label').text(satuan);
            });

            function updateRemoveButtons() {
                let rows = $('.item-row');
                if (rows.length === 1) {
                    rows.find('.btn-remove-item').hide();
                } else {
                    rows.find('.btn-remove-item').show();
                }
            }

            updateRemoveButtons();
        });
    </script>
</body>
</html>
