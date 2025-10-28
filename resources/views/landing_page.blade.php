<!DOCTYPE html>
<html>
<head>
    <title>Inventaris Gudang & Link Zoom PUSDA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
</head>
<body>

<div class="container mt-5">
    <div class="jumbotron">
        <h1 class="display-4">Selamat Datang di Sistem PUSDA</h1>
        <p class="lead">Silakan pilih jenis permintaan yang Anda butuhkan.</p>
        <hr class="my-4">
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-primary btn-lg btn-block" href="{{ route('request.barang.create') }}" role="button">
                    <i class="fas fa-boxes"></i> REQUEST BARANG
                </a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-info btn-lg btn-block" href="{{ route('request.zoom.create') }}" role="button">
                    <i class="fas fa-video"></i> REQUEST LINK ZOOM
                </a>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <p>Untuk Admin/Super Admin: <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</div>

</body>
</html>