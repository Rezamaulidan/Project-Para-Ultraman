<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Rekan Kerja - SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* === NAVBAR STYLING === */
        .navbar-navy {
            background-color: #001931;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.25rem;
        }

        /* === CARD STYLING === */
        .staff-card {
            border: none;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .staff-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #001931, #0056b3);
            transition: height 0.3s ease;
        }

        .staff-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 25, 49, 0.15);
        }

        .staff-card:hover::before {
            height: 6px;
        }

        /* Highlight kartu sendiri */
        .staff-card.is-me {
            border: 2px solid #FFD700;
            background-color: #fffff8;
        }

        .staff-card.is-me::before {
            background: #FFD700;
        }

        /* Avatar */
        .staff-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-bottom: 1rem;
        }

        .staff-name {
            font-weight: 700;
            color: #212529;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .staff-role {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .btn-view {
            background-color: #f1f5f9;
            color: #001931;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 20px;
            border-radius: 50px;
            transition: all 0.2s;
            display: inline-block;
        }

        .staff-card:hover .btn-view {
            background-color: #001931;
            color: #fff;
        }

        /* Footer */
        footer {
            margin-top: auto;
            background-color: white;
            padding: 1.5rem 0;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

    {{-- 1. NAVBAR --}}
    <nav class="navbar navbar-navy navbar-dark sticky-top">
        <div class="container">

            {{-- 🛑 LOGO BRAND (GAMBAR) --}}
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-simk.png') }}" alt="Logo" style="width: 40px; height: auto;"
                    class="me-2">
                <span>SIMK <span class="fw-light">Staff</span></span>
            </a>

            {{-- 🛑 TOMBOL MENU UTAMA (PENGGANTI KELUAR) --}}
            <div class="ms-auto">
                <a href="{{ route('staff.menu') }}"
                    class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow-sm"
                    style="color: #001931 !important;">
                    <i class="fas fa-th-large me-2"></i> Menu Utama
                </a>
            </div>

        </div>
    </nav>

    {{-- 2. KONTEN UTAMA --}}
    <div class="container py-5">

        {{-- Header Halaman (Centered) --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-2">Daftar Staf</h2>
            <p class="text-muted mb-0">Pilih profil staf untuk melihat detail informasi.</p>
        </div>

        {{-- Grid Staf (Centered) --}}
        <div class="row g-4 justify-content-center">
            @forelse($stafs as $staf)
                <div class="col-md-6 col-lg-4 col-xl-3">

                    {{-- Logic Kartu --}}
                    @php
                        // Cek sederhana jika ID staff sama (karena shared account, ini hanya visualisasi jika session mendukung)
                        $isMe =
                            Auth::user()->username == 'staf' &&
                            Auth::user()->staf &&
                            Auth::user()->staf->id_staf == $staf->id_staf;
                    @endphp

                    <a href="{{ route('staff.lihat_profil', ['id' => $staf->id_staf]) }}"
                        class="staff-card p-4 text-center h-100 {{ $isMe ? 'is-me' : '' }}">

                        @if ($isMe)
                            <span
                                class="badge bg-warning text-dark position-absolute top-0 end-0 mt-2 me-2 shadow-sm">Anda</span>
                        @endif

                        {{-- Foto --}}
                        <div class="position-relative d-inline-block">
                            @if ($staf->foto_staf)
                                <img src="{{ asset('storage/' . $staf->foto_staf) }}" alt="{{ $staf->nama_staf }}"
                                    class="staff-avatar">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($staf->nama_staf) }}&background=001931&color=fff&size=128&bold=true"
                                    alt="{{ $staf->nama_staf }}" class="staff-avatar">
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="mt-2">
                            <div class="staff-role">
                                <i class="fas fa-id-badge me-1"></i> {{ ucfirst($staf->jadwal) }} Shift
                            </div>
                            <h5 class="staff-name text-truncate mb-1">{{ $staf->nama_staf }}</h5>

                            {{-- No HP --}}
                            <p class="text-muted small mb-3">{{ $staf->no_hp }}</p>

                            <span class="btn-view">
                                Detail Profil
                            </span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="bg-white p-5 rounded-4 shadow-sm d-inline-block">
                        <i class="fas fa-users-slash fa-4x text-muted opacity-25 mb-3"></i>
                        <h5 class="fw-bold text-muted">Data Staf Kosong</h5>
                        <p class="text-muted small">Belum ada staf yang terdaftar di sistem.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
