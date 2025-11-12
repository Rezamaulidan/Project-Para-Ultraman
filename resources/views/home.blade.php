<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen Kos</title>

    {{-- WAJIB: Link untuk memuat file CSS Bootstrap agar styling berfungsi --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS untuk card kamar (disalin dari dashboard_booking) --}}
    <style>
        .card-kamar-body {
            background-color: #fff7f7;
        }
        .card {
            transition: all 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>

    {{-- 1. Memanggil navbar ASLI untuk tamu (tidak diubah) --}}
    @include('partials.navbar')

    {{-- 2. Konten utama halaman (Grid Kamar dari dashboard_booking) --}}
    <div class="container my-4">

        <h2 class="mb-4">Informasi Kamar</h2>

        {{-- Baris untuk card kamar --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            {{-- Card Kamar 1 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 1">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 1</h5>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 1</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 2 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 2">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 2</h5>
                            <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill fw-medium">Terisi</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 1</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 3 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 3">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 3</h5>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 1</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 4 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-atas.png') }}" class="card-img-top" alt="Kamar 4">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 4</h5>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 2</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.000.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 11 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 11">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 11</h5>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 2</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 12 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 12">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 12</h5>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 2</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 13 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 13">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 13</h5>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 2</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

            {{-- Card Kamar 14 (Contoh) --}}
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset('img/kamar-bawah.png') }}" class="card-img-top" alt="Kamar 14">
                    <div class="card-body card-kamar-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">Kamar No. 14</h5>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill fw-medium">Kosong</span>
                        </div>
                        <p class="card-text text-muted mb-1">Lantai: 2</p>
                        <p class="card-text fw-bold fs-5 mb-0">Rp 1.500.000</p>
                        <p class="card-text text-muted small">/Perbulan</p>
                    </div>
                </div>
            </div>

        </div> {{-- End row --}}

    </div> {{-- End container --}}


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
