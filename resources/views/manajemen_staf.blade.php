<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Staf SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Card dengan Gradient */
        .profile-header {
            background: linear-gradient(135deg, #001931 0%, #003366 100%);
            height: 180px;
            border-radius: 15px 15px 0 0;
            position: relative;
        }

        /* Card Utama */
        .main-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-top: -50px;
            /* Efek overlap */
            background: white;
            overflow: visible;
        }

        /* Avatar */
        .profile-avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            object-fit: cover;
            background-color: #fff;
            margin-top: -75px;
            /* Menarik foto ke atas */
        }

        /* Info Box */
        .info-box {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            height: 100%;
        }

        .info-box:hover {
            background-color: #fff;
            border-color: #001931;
            box-shadow: 0 5px 15px rgba(0, 25, 49, 0.08);
            transform: translateY(-2px);
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #212529;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            background-color: #e6f0ff;
            color: #001931;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        /* Tombol Kembali */
        .btn-back {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
    </style>
</head>

<body>

    <div class="container py-4">

        {{-- 1. Header Navigasi Kecil --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manajemen Profil</h4>
                <p class="text-muted small mb-0">Kelola informasi akun staf Anda</p>
            </div>
            <a href="{{ route('staff.menu') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Menu
            </a>
        </div>

        {{-- 2. Alert Sukses --}}
        @if (session('sukses') || session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fs-4 me-3"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('sukses') ?? session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 3. Kartu Profil Utama --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            {{-- Header Banner --}}
            <div class="profile-header d-flex align-items-start justify-content-between p-4">
                <div class="text-white opacity-75 small">
                    <i class="fas fa-id-badge me-1"></i> ID: #{{ Auth::user()->username }}
                </div>
            </div>

            <div class="card-body px-4 pb-5">
                <div class="row justify-content-center text-center">
                    <div class="col-12">
                        {{-- Foto Profil / Avatar --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->staf->nama_staf ?? Auth::user()->username) }}&background=001931&color=fff&size=256&bold=true"
                            alt="Avatar" class="profile-avatar mb-3">

                        {{-- Nama & Role --}}
                        <h3 class="fw-bold text-dark mb-1">
                            {{ Auth::user()->staf->nama_staf ?? Auth::user()->username }}
                        </h3>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            <i class="fas fa-user-shield me-1"></i> Staf Operasional
                        </span>
                    </div>
                </div>

                {{-- Grid Informasi Detail --}}
                <div class="row g-3 mt-4 justify-content-center">

                    {{-- Kolom 1: Username --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="info-label">Username Login</div>
                                    <div class="info-value">{{ Auth::user()->username }}</div>
                                </div>
                                <div class="icon-wrapper">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 2: Email --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="info-label">Alamat Email</div>
                                    <div class="info-value text-break">{{ Auth::user()->email ?? '-' }}</div>
                                </div>
                                <div class="icon-wrapper">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 3: No HP --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="info-label">No WhatsApp</div>
                                    <div class="info-value">{{ Auth::user()->staf->no_hp ?? '-' }}</div>
                                </div>
                                <div class="icon-wrapper">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 4: Jadwal Kerja --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="info-label">Shift / Jadwal</div>
                                    <div class="info-value text-uppercase text-primary">
                                        {{ Auth::user()->staf->jadwal ?? 'Belum Diatur' }}
                                    </div>
                                </div>
                                <div class="icon-wrapper bg-primary text-white">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Tombol Edit --}}
                <div class="text-center mt-5">
                    <a href="{{ route('staff.manajemen.edit') }}"
                        class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm fw-bold"
                        style="background-color: #001931; border-color: #001931;">
                        <i class="fas fa-pen-to-square me-2"></i> Edit Profil Saya
                    </a>
                </div>

            </div>
        </div>

        {{-- Footer Kecil --}}
        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} Sistem Informasi Manajemen Kos.
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
