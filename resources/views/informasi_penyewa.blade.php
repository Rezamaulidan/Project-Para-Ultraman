<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Penyewa - SIMK</title>

    {{-- Link untuk memuat file CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Link Font Awesome (agar ikon berfungsi) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Mengatur warna background utama */
        body {
            background-color: #f0f2f5; /* Abu-abu muda */
        }

        /* Header kustom (biru) */
        .header-penyewa {
            background-color: #0089FF; /* Biru dari navbar Anda */
            color: white;
            padding: 1rem;
        }

        /* Profile card (biru gradient) */
        .profile-card {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        /* Avatar icon kustom */
        .profile-avatar {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            color: #0d6efd;
            font-size: 2.5rem;
        }

        /* Styling untuk tab navigasi */
        .nav-tabs .nav-link {
            color: #6c757d; /* Warna abu-abu untuk tab tidak aktif */
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .nav-tabs .nav-link.active {
            color: #000;
            font-weight: 600;
            background-color: white;
            border-bottom-color: white;
        }
        .nav-tabs {
            border-bottom: 0;
        }

        /* Styling untuk input readonly agar terlihat seperti di gambar */
        .form-control[readonly] {
            background-color: #f8f9fa; /* Abu-abu sangat muda */
            opacity: 1;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>

    {{-- 1. Memanggil navbar utama penyewa (dari file sebelumnya) --}}
    @include('partials.navbar_penyewa')

    {{-- 2. Header Halaman (Biru) --}}
    <div class="header-penyewa shadow-sm">
        <div class="container-fluid d-flex align-items-center">
            {{-- Tombol Kembali (Ganti '#' dengan rute kembali, misal 'penyewa.dashboard') --}}
            <a href="#" class="text-white me-3">
                <i class="fa-solid fa-arrow-left fa-lg"></i>
            </a>
            <h5 class="mb-0"></h5>
        </div>
    </div>

    {{-- 3. Tab Navigasi (Abu-abu) --}}
    <div class="container-fluid bg-light pt-2">
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    Informasi Penyewa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-pencil me-1"></i>
                    informasi keamanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-money-check-dollar me-1"></i>
                    Menu Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-money-check-dollar me-1"></i>
                    Informasi Kamar
                </a>
            </li>
        </ul>
    </div>

    {{-- 4. Konten Utama (Informasi Penyewa) --}}
    <div class="container-fluid p-3 p-md-4">

        {{-- Profile Card (Biru) --}}
        <div class="card profile-card border-0 p-3 mb-4">
            <div class="card-body text-center">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h4 class="mb-0">Denis</h4>
            </div>
        </div>

        {{-- Detail Card (Putih) --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <div class="row g-4">

                    {{-- Kolom Kiri --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nomorKamar" class="form-label small text-muted">Nomor Kamar</label>
                            <input type="text" id="nomorKamar" class="form-control" value="1" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalLahir" class="form-label small text-muted">Tanggal Lahir</label>
                            <input type="text" id="tanggalLahir" class="form-control" value="13-02-2004" readonly>
                        </div>
                        <div>
                            <label class="form-label small text-muted">Jenis Kelamin</label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    {{-- 'checked' dan 'disabled' akan dikontrol oleh data Anda nanti --}}
                                    <input class="form-check-input" type="radio" name="jenisKelamin" id="laki" value="Laki-laki" checked disabled>
                                    <label class="form-check-label" for="laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenisKelamin" id="perempuan" value="Perempuan" disabled>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="noTelepon" class="form-label small text-muted">No Telepon</label>
                            <input type="text" id="noTelepon" class="form-control" value="081387863100" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tenggat" class="form-label small text-muted">Tenggat Penyewaan</label>
                            <input type="text" id="tenggat" class="form-control" value="31 Desember 2026" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tagihan" class="form-label small text-muted">Tagihan</label>
                            <input type="text" id="tagihan" class="form-control" value="Rp 1.000.000" readonly>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Link untuk memuat JavaScript Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
