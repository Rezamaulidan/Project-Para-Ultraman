<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen Kos</title>

    {{-- WAJIB: Link untuk memuat file CSS Bootstrap agar styling berfungsi --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    {{-- 1. Memanggil dan menampilkan navbar --}}
    @include('partials.navbar')

    {{-- 2. Konten utama halaman home --}}
    <div class="container">

        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-10 text-center">

                {{-- Logo Besar --}}
                <div class="mb-4">

                    <img src="{{ asset('img/logo-simk.png') }}" alt="Logo SIMK" style="width: 180px; height: auto;">
                </div>

                {{-- Judul --}}
                <h1 class="display-4 fw-bold mb-3">
                    Sistem Informasi Manajemen Kos
                </h1>

                {{-- Deskripsi --}}
                <p class="lead text-muted fw-bold">
                    SIMK adalah aplikasi web yang dirancang untuk membantu pemilik kos, penyewa, dan staf dalam mengelola kegiatan operasional kos secara efisien. Sistem ini memudahkan pengelolaan data kamar, informasi penyewa, pembayaran, pencatatan administrasi, dan memudahkan staf dalam bekerja.
                </p>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
