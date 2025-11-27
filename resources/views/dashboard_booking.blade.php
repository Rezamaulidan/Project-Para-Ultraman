<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Booking - Sistem Informasi Manajemen Kos</title>

    <!-- Link untuk memuat file CSS Bootstrap agar styling berfungsi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS untuk card kamar -->
    <style>
        /* Wrapper utama tetap flexbox + scroll vertical jika terlalu banyak */
        .cards-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
            margin-top: 20px;
            padding: 0 15px;
        }

        /* Card item menggunakan flex basis + grow agar responsif */
        .card-item {
            flex: 0 1 calc(25% - 24px);
            max-width: 320px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.12);
        }

        /* Gambar tetap konsisten */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-kamar-body {
            background-color: #fff7f7;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-text {
            color: #6c757d;
        }

        .badge {
            font-size: 0.9rem;
        }

        .fasilitas-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .fasilitas-item {
            background-color: #e7f5e7;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .card {
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15) !important;
        }

        /* ──────────────────────────────
       RESPONSIVE BREAKPOINTS
       ────────────────────────────── */

        /* Large devices (≥1200px) → 4 cards */
        @media (max-width: 1399.98px) {
            .card-item {
                flex: 0 1 calc(25% - 24px);
            }
        }

        /* Medium devices (992px – 1199px) → 3 cards */
        @media (max-width: 1199.98px) {
            .card-item {
                flex: 0 1 calc(33.333% - 24px);
            }
        }

        /* Tablet (768px – 991px) → 2 cards */
        @media (max-width: 991.98px) {
            .card-item {
                flex: 0 1 calc(50% - 24px);
            }
        }

        /* Mobile (<768px) → 1 card */
        @media (max-width: 767.98px) {
            .card-item {
                flex: 0 1 calc(100% - 24px);
                max-width: 100%;
            }

            .cards-wrapper {
                gap: 20px;
                padding: 0 10px;
            }
        }

        /* Extra small (<576px) – tetap 1 card, tapi card lebih lebar */
        @media (max-width: 575.98px) {
            .card-item {
                flex: 0 1 100%;
            }
        }
    </style>
</head>

<body>

    {{-- Memanggil navbar booking (untuk user yang sudah login) --}}
    @include('partials.navbar_booking')

    {{-- Konten utama halaman --}}
    <div class="container my-4">
        <h2 class="mb-4 text-center">Informasi Kamar</h2>

        <!-- Cards Wrapper -->
        <div class="cards-wrapper">
            @forelse ($kamars as $kamar)
                <div class="card-item">
                    <div class="card shadow-sm border-0 h-100">
                        <!-- Foto Kamar -->
                        <img src="{{ $kamar->foto_kamar ? asset($kamar->foto_kamar) : asset('img/kamar-atas.png') }}"
                            class="card-img-top" alt="Kamar {{ $kamar->no_kamar }}"
                            onerror="this.src='{{ asset('img/kamar-atas.png') }}'">

                        <div class="card-body card-kamar-body">
                            {{-- Bagian Atas Card --}}
                            <div>
                                <!-- Nomor & Status -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0">Kamar No. {{ $kamar->no_kamar }}</h5>
                                    <span
                                        class="badge rounded-pill fw-medium
                                            {{ strtolower($kamar->status) == 'tersedia' ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
                                        {{ ucfirst($kamar->status) }}
                                    </span>
                                </div>

                                <!-- Lantai -->
                                <p class="card-text text-muted mb-1">
                                    <i class="bi bi-layers me-1"></i>Lantai: {{ $kamar->lantai }}
                                </p>

                                <!-- Harga -->
                                <p class="card-text fw-bold fs-5 mb-0">
                                    Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                                </p>
                                <p class="card-text text-muted small">/Perbulan</p>

                                <!-- Tipe & Ukuran -->
                                <p class="card-text text-muted small mb-1">
                                    <i class="bi bi-door-open me-1"></i>{{ ucfirst($kamar->tipe_kamar) }}
                                </p>
                                <p class="card-text text-muted small mb-0">
                                    <i class="bi bi-rulers me-1"></i>{{ $kamar->ukuran }}
                                </p>

                                @if ($kamar->fasilitas)
                                    <div class="mt-3">
                                        <strong class="text-dark d-block mb-2">Fasilitas:</strong>
                                        <div class="fasilitas-list">
                                            @foreach (explode(',', $kamar->fasilitas) as $item)
                                                <span class="fasilitas-item">
                                                    {{ trim($item) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <small class="text-muted">Fasilitas belum diisi</small>
                                @endif
                            </div>

                            {{-- Bagian Bawah Card (Tombol Pesan) --}}
                            <div class="mt-3">
                                @if (strtolower($kamar->status) == 'tersedia')
                                    <a href="{{ route('penyewa.booking.create', $kamar->no_kamar) }}"
                                        class="btn btn-primary w-100 rounded-pill">
                                        <i class="bi bi-bookmark-check me-1"></i> Pesan Kamar
                                    </a>
                                @else
                                    <button class="btn btn-secondary w-100 rounded-pill" disabled>
                                        <i class="bi bi-ban me-1"></i> Sudah Terisi
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="display-6 text-muted">Belum ada kamar tersedia</p>
                </div>
            @endforelse
        </div>

        <!-- KONTAK PEMILIK KOS – MUNCUL UNTUK SEMUA ORANG -->
        <div class="row justify-content-center my-5">
            <div class="col-12 col-md-8 col-lg-4">
                <div class="text-center my-4 py-4 bg-light rounded-4 border">
                    <h4 class="mb-4 text-dark">Kontak Pemilik Kos</h4>
                    <p class="fs-3 fw-bold mb-3">
                        {{ $pemilik->no_hp }}
                    </p>

                    <p class="fs-3 fw-bold">
                        {{ $pemilik->nama_pemilik }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
