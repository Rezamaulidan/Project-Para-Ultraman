<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penyewa - SIMK</title>

    {{-- Link untuk memuat file CSS Bootstrap agar styling berfungsi --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Link Font Awesome (agar ikon di navbar_penyewa berfungsi) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    </style>
</head>
<body>

    {{-- 1. Memanggil navbar BARU (navbar_penyewa) --}}
    @include('partials.navbar_penyewa')

    {{-- 2. Konten utama halaman --}}
    <div class="container my-4">

        <h2 class="mb-4">Selamat Datang!</h2>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Pemesanan Anda Telah Disetujui</h4>
            <p>Selamat datang sebagai penyewa kos. Anda sekarang memiliki akses penuh ke fitur penyewa.</p>
            <hr>
            <p class="mb-0">Silakan gunakan **tombol menu di kanan atas** untuk mengakses informasi keamanan, menu pembayaran, dan lainnya.</p>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    </div> {{-- End container --}}


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
