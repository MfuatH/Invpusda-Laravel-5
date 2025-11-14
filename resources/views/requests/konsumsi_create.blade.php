<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Makanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('images/background.jpeg'); /* ganti sesuai lokasi gambar */
            background-size: cover;
            background-position: center;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .outer-container {
            max-width: 950px;
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .left-panel {
            background: transparent;
            padding: 30px;
            text-align: center;
            color: white;
        }

        .left-panel img.logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .left-panel h2 {
            font-size: 2rem;
            font-weight: 700;
        }

        .left-panel img.illustration {
            max-width: 90%;
            margin-top: 20px;
        }

        /* Panel kanan form */
        .right-panel {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
        }

        .form-group {
            position: relative;
        }

        .form-group i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #777;
        }

        .form-control {
            padding-left: 40px;
            background: #f4f7f6;
            border: none;
            height: 45px;
            border-radius: 8px;
        }

        textarea.form-control {
            height: auto;
            padding-top: 12px;
        }

        .btn-custom {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 8px;
        }

        @media(max-width: 768px) {
            .right-panel {
                border-radius: 0;
            }
            .left-panel img.illustration {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="outer-container">
    <div class="row g-0">

        <!-- Panel kiri -->
        <div class="col-md-5 left-panel">
            <img src="images/logo.png" class="logo" alt="Logo">
            <h2>Form Pemesanan</h2>
            <img src="images/food.png" class="illustration" alt="Ilustrasi Makanan">
        </div>

        <!-- Panel kanan -->
        <div class="col-md-7 p-3">
            <div class="right-panel">

                <h4 class="mb-4 fw-bold">Pemesanan Makanan</h4>

                <form>

                    <div class="form-group mb-3">
                        <i class="fa fa-user"></i>
                        <input type="text" class="form-control" placeholder="Nama Pemesan" required>
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-file-alt"></i>
                        <input type="text" class="form-control" placeholder="Keperluan" required>
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-calendar"></i>
                        <input type="datetime-local" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-location-dot"></i>
                        <input type="text" class="form-control" placeholder="Tempat" required>
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-users"></i>
                        <input type="number" class="form-control" placeholder="Jumlah Peserta" required>
                    </div>

                    <label class="mb-1">Jenis Konsumsi</label><br>
                    <div class="d-flex gap-3 mb-3">
                        <label><input type="checkbox"> Makan</label>
                        <label><input type="checkbox"> Minum</label>
                        <label><input type="checkbox"> Snack</label>
                    </div>

                    <div class="form-group mb-3">
                        <i class="fa fa-comment"></i>
                        <textarea class="form-control" rows="2" placeholder="Keterangan (opsional)"></textarea>
                    </div>

                    <div class="text-end mt-4">
                        <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
                        <button type="submit" class="btn btn-primary btn-custom">Kirim Pemesanan</button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
