<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Informasi Manajemen Kos</title>

    {{-- WAJIB: Link untuk memuat file CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- WAJIB: Link untuk Font Awesome (jika Anda ingin ikon) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Tambahan CSS untuk card kamar --}}
    <style>
        .card-kamar-body {
            /* Ini warna pink muda seperti di screenshot Anda */
            background-color: #fff7f7;
            /* Pastikan card body bisa menampung tombol di bawah */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card {
            transition: all 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
        /* Style untuk tombol yang dinonaktifkan */
        .btn-terisi {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    {{-- 1. Memanggil navbar BARU (khusus untuk user yang sudah login) --}}
    @include('partials.navbar_booking')

    {{-- 2. Konten utama halaman (Grid Kamar) --}}
    <div class="container my-4">

        <h2 class="mb-4">Informasi Kamar</h2>

        {{-- Baris untuk card kamar --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            {{-- Card Kamar 1 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 1">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 1</h5>
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 1</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100 rounded-pill">
                                Pesan Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 2 (Contoh Terisi) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 2">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 2</h5>
                                {{-- CONTOH JIKA TERISI --}}
                                <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill fw-medium">Terisi</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 1</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            {{-- CONTOH TOMBOL JIKA TERISI --}}
                            <button class="btn btn-secondary w-100 rounded-pill" disabled>
                                <i class="fa-solid fa-ban me-1"></i> Sudah Terisi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 3 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 3">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 3</h5>
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 1</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100 rounded-pill">
                                Pesan Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 4 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 4">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 4</h5>
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100 rounded-pill">
                                Pesan Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 11 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 11">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 11</h5>
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100 rounded-pill">
                                Pesan Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 12 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 12">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 12</h5>
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100 rounded-pill">
                                Pesan Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 13 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 13">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 13</h5>
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100 rounded-pill">
                                Pesan Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 14 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 14">
                    <div class="card-body card-kamar-body">
                        {{-- Bagian Atas Card --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">Kamar No. 14</h5>
                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                            </div>
                            <p class="card-text text-muted mb-1">Lantai: 2</p>
                            <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                            <p class="card-text text-muted small">/Perbulan</p>
                        </div>

                        {{-- Bagian Bawah Card (Tombol) --}}
                        <div class="mt-3">
                            <a href="#" class="btn btn-primary w-100 rounded-pill">
                                Pesan Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- End row --}}

    </div> {{-- End container --}}


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
