<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penyewa - SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-booking {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-booking:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</head>

<body>

    @include('partials.navbar_penyewa')

    <div class="container my-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark">Dashboard Saya</h2>
                <p class="text-muted">Halo, {{ Auth::user()->name ?? 'Penyewa' }}! Pantau status kos Anda di sini.</p>
            </div>

            {{-- LOGIKA TOMBOL CARI KAMAR BARU --}}
            {{-- Hanya tampil jika BELUM punya kamar aktif (Lunas) --}}
            @if (!$sudahPunyaKamarAktif)
                <a href="{{ route('dashboard.booking') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="fas fa-plus me-2"></i> Cari Kamar Baru
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- KOLOM DAFTAR BOOKING --}}
            <div class="col-lg-8">

                @forelse($bookingsSaya as $booking)
                    <div class="card border-0 shadow-sm rounded-4 mb-4 card-booking overflow-hidden">
                        <div class="card-body p-4">
                            <div class="row">

                                {{-- FOTO KAMAR --}}
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <img src="{{ $booking->kamar->foto_kamar ? asset($booking->kamar->foto_kamar) : asset('img/kamar-atas.png') }}"
                                        class="img-fluid rounded-3 shadow-sm w-100"
                                        style="height: 100px; object-fit: cover;" alt="Kamar"
                                        onerror="this.src='https://placehold.co/400x300?text=Foto+Kamar'">
                                </div>

                                {{-- INFO BOOKING --}}
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <h5 class="fw-bold mb-1">Kamar No. {{ $booking->no_kamar }}</h5>
                                    <div class="text-muted small mb-1">
                                        <i class="far fa-calendar-alt me-2"></i>
                                        Mulai: {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-clock me-2"></i>
                                        Durasi: {{ $booking->durasi_sewa }} Bulan
                                    </div>
                                    <h6 class="fw-bold text-primary mt-3 mb-0">
                                        Rp {{ number_format($booking->nominal, 0, ',', '.') }}
                                    </h6>
                                </div>

                                {{-- STATUS & TOMBOL AKSI --}}
                                <div class="col-md-4 text-md-end">

                                    {{-- SKENARIO 1: LUNAS --}}
                                    @if ($booking->status_booking == 'lunas')
                                        <div class="mb-3">
                                            <span class="badge bg-success px-3 py-2 rounded-pill mb-1">
                                                <i class="fas fa-check-circle me-1"></i> AKTIF / LUNAS
                                            </span>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm w-100 rounded-pill mb-2"
                                            onclick="alert('Fitur download PDF akan segera hadir!')">
                                            <i class="fas fa-file-invoice me-1"></i> Kuitansi
                                        </button>
                                        <a href="https://wa.me/6281234567890" target="_blank"
                                            class="btn btn-outline-danger btn-sm w-100 rounded-pill">
                                            <i class="fas fa-tools me-1"></i> Lapor Masalah
                                        </a>

                                        {{-- SKENARIO 2: CONFIRMED (BAYAR) --}}
                                    @elseif($booking->status_booking == 'confirmed')
                                        <div class="mb-3">
                                            <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">
                                                <i class="fas fa-check me-1"></i> DISETUJUI
                                            </span>
                                        </div>
                                        <a href="{{ route('penyewa.bayar', $booking->id_booking) }}"
                                            class="btn btn-success btn-sm w-100 rounded-pill fw-bold shadow-sm py-2">
                                            Bayar Sekarang <i class="fas fa-arrow-right ms-1"></i>
                                        </a>

                                        {{-- SKENARIO 3: PENDING --}}
                                    @elseif($booking->status_booking == 'pending')
                                        <div class="mb-3">
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-2">
                                                <i class="fas fa-hourglass-half me-1"></i> MENUNGGU
                                            </span>
                                        </div>
                                        <button class="btn btn-secondary btn-sm w-100 rounded-pill" disabled>
                                            Menunggu Konfirmasi
                                        </button>

                                        {{-- SKENARIO 4: DITOLAK (REJECTED) --}}
                                    @elseif($booking->status_booking == 'rejected')
                                        <div class="mb-3">
                                            <span class="badge bg-danger px-3 py-2 rounded-pill mb-2">
                                                <i class="fas fa-times-circle me-1"></i> DITOLAK
                                            </span>
                                        </div>
                                        <p class="small text-danger mb-2">Maaf, booking anda ditolak</p>
                                        {{-- Tombol Cari Kamar Kos Khusus Card Ini --}}
                                        <a href="{{ route('dashboard.booking') }}"
                                            class="btn btn-primary btn-sm w-100 rounded-pill shadow-sm">
                                            <i class="fas fa-search me-1"></i> Cari Kamar Lain
                                        </a>
                                    @endif

                                </div>
                            </div>

                            {{-- INFO HUNIAN (KHUSUS LUNAS) --}}
                            @if ($booking->status_booking == 'lunas')
                                <hr class="my-3 border-light">
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <div class="bg-light p-3 rounded-4 border h-100">
                                            <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">
                                                <i class="fas fa-key me-2 text-warning"></i>Akses Kost
                                            </h6>
                                            <div
                                                class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                                <span class="text-muted small"><i
                                                        class="fas fa-wifi me-2"></i>Wi-Fi</span>
                                                <span class="fw-bold text-dark small">Kost_Berkah_5G</span>
                                            </div>
                                            <div
                                                class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                                <span class="text-muted small"><i
                                                        class="fas fa-lock me-2"></i>Password</span>
                                                <span class="fw-bold text-dark small">sukses2024</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div
                                            class="alert alert-info border-0 rounded-4 d-flex align-items-center p-3 h-100 mb-0">
                                            <div class="me-3"><i class="fas fa-calendar-alt fa-2x text-info"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted fw-bold" style="font-size: 0.75rem;">Jatuh
                                                    Tempo</div>
                                                <div class="fw-bold text-dark">
                                                    {{ \Carbon\Carbon::parse($booking->tanggal)->addMonths(1)->format('d M Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    {{-- TAMPILAN JIKA BELUM ADA BOOKING SAMA SEKALI --}}
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                        <div class="mb-3"><i class="fas fa-bed fa-4x text-muted opacity-25"></i></div>
                        <h5 class="fw-bold text-muted">Belum ada riwayat booking</h5>
                        <p class="text-muted small">Anda belum menyewa kamar apapun saat ini.</p>
                        <a href="{{ route('dashboard.booking') }}" class="btn btn-primary px-4 rounded-pill mt-2">
                            Cari Kamar Kos
                        </a>
                    </div>
                @endforelse

            </div>

            {{-- SIDEBAR MENU --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <i class="fab fa-whatsapp fa-3x mb-3 opacity-75"></i>
                        <h5 class="fw-bold">Butuh Bantuan?</h5>
                        <p class="small mb-3 text-white-50">Hubungi pemilik kos jika ada kendala mendesak.</p>
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="btn btn-light text-primary fw-bold rounded-pill w-100 shadow-sm">
                            Chat Pemilik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
