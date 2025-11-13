<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Makanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Form Pemesanan Makanan</h4>
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                    <input type="text" id="nama_pemesan" class="form-control" placeholder="Masukkan nama Anda">
                </div>

                <div class="mb-3">
                    <label for="keperluan" class="form-label">Keperluan</label>
                    <input type="text" id="keperluan" class="form-control" placeholder="Contoh: Rapat koordinasi, acara dinas, dsb">
                </div>

                <div class="mb-3">
                    <label for="waktu_kegiatan" class="form-label">Waktu Kegiatan</label>
                    <input type="datetime-local" id="waktu_kegiatan" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="tempat" class="form-label">Tempat</label>
                    <input type="text" id="tempat" class="form-control" placeholder="Masukkan lokasi kegiatan">
                </div>

                <div class="mb-3">
                    <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                    <input type="number" id="jumlah_peserta" class="form-control" placeholder="Masukkan jumlah peserta">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="jenis_makan" value="makan">
                        <label class="form-check-label" for="jenis_makan">Makan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="jenis_minum" value="minum">
                        <label class="form-check-label" for="jenis_minum">Minum</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="jenis_snack" value="snack">
                        <label class="form-check-label" for="jenis_snack">Snack</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea id="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan tambahan jika ada"></textarea>
                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-success">Kirim Pemesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
