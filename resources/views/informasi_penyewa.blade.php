<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Penyewa - SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Link Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Background utama */
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-penyewa {
            background-color: #001931;
            color: white;
            padding: 1rem;
        }

        /* Kartu Profil (Gradient Biru) */
        .profile-card {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 10px;
        }

        /* Lingkaran Avatar (Container) */
        .profile-avatar-container {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem auto;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            /* Agar gambar tidak bocor */
            display: flex;
            justify-content: center;
            align-items: center;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        /* Gambar Profil */
        .profile-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Inisial Huruf (Jika tidak ada foto) */
        .profile-avatar-initials {
            color: #0056b3;
            font-size: 2.5rem;
            font-weight: 700;
            font-family: sans-serif;
            text-transform: uppercase;
        }

        /* Tombol Edit Profil */
        .btn-edit-profile {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
        }

        .btn-edit-profile:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        /* Animasi Sliding Indicator untuk Tab */
        .nav-tabs {
            position: relative;
            border-bottom: 0;
        }

        .nav-tabs::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: var(--indicator-left, 0);
            width: var(--indicator-width, 0);
            height: 3px;
            background-color: #0089FF;
            transition: all 0.3s ease;
            border-radius: 3px 3px 0 0;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: #000;
            font-weight: 600;
            background-color: white;
            border-bottom-color: white;
        }

        /* Styling Input Readonly */
        .form-control[readonly] {
            background-color: #f8f9fa;
            opacity: 1;
            border-left: none;
        }

        /* Styling Group Input (Icon + Field) */
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #0089FF;
        }

        /* Styling Label Form */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    {{-- 1. Navbar --}}
    @include('partials.navbar_menu_penyewa')

    {{-- 2. Header Halaman --}}
    <div class="header-penyewa shadow-sm">
        <div class="container-fluid d-flex align-items-center">
        </div>
    </div>

    {{-- 3. Tab Navigasi --}}
    <div class="container-fluid bg-light pt-2 shadow-sm">
        <ul class="nav nav-tabs nav-fill justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('penyewa.informasi') }}">
                    <i class="fa-solid fa-circle-info me-2"></i>Informasi Penyewa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.keamanan') }}">
                    <i class="fa-solid fa-shield-halved me-2"></i>Informasi Keamanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.pembayaran') }}">
                    <i class="fa-solid fa-money-check-dollar me-2"></i>Menu Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.kamar') }}">
                    <i class="fa-solid fa-box me-2"></i>Informasi Kamar
                </a>
            </li>
        </ul>
    </div>

    {{-- 4. Konten Utama --}}
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-7">

                {{-- Kartu Profil (Atas) --}}
                <div class="card profile-card border-0 p-4 mb-4 shadow">
                    <div class="card-body text-center">

                        {{-- [BARU] Logika Tampilan Foto Profil --}}
                        <div class="profile-avatar-container">
                            @if ($penyewa->foto_profil && file_exists(public_path('storage/' . $penyewa->foto_profil)))
                                {{-- Tampilkan Foto --}}
                                <img src="{{ asset('storage/' . $penyewa->foto_profil) }}"
                                    alt="Foto Profil {{ $penyewa->nama_penyewa }}" class="profile-avatar-img">
                            @else
                                {{-- Tampilkan Inisial Huruf --}}
                                <span class="profile-avatar-initials">
                                    {{ strtoupper(substr($penyewa->nama_penyewa ?? 'U', 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <h3 class="mb-1 fw-bold">{{ $penyewa->nama_penyewa ?? 'Nama Penyewa' }}</h3>
                        <p class="mb-3 opacity-75 small"></p>

                        <a href="{{ route('penyewa.edit_informasi') }}" class="btn-edit-profile">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Data Diri
                        </a>
                    </div>
                </div>

                {{-- Kartu Detail Informasi (Bawah) --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 pb-2 border-bottom text-primary fw-bold">
                            <i class="fa-solid fa-list-ul me-2"></i>Detail Informasi
                        </h5>

                        <div class="row g-4">

                            {{-- 1. Nomor Kamar --}}
                            <div class="col-12">
                                <div class="mb-1">
                                    <label for="nomorKamar" class="form-label">Nomor Kamar</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-door-open"></i></span>
                                        <input type="text" id="nomorKamar" class="form-control"
                                            value="{{ $penyewa->nomor_kamar ?? '-' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. No Telepon --}}
                            <div class="col-12">
                                <div class="mb-1">
                                    <label for="noTelepon" class="form-label">No Telepon (WhatsApp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-brands fa-whatsapp"></i></span>
                                        <input type="text" id="noTelepon" class="form-control"
                                            value="{{ $penyewa->no_hp ?? '-' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Jenis Kelamin --}}
                            <div class="col-12">
                                <div>
                                    <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-venus-mars"></i></span>
                                        <input type="text" id="jenisKelamin" class="form-control"
                                            value="{{ $penyewa->jenis_kelamin ?? '-' }}" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script untuk Animasi Indikator Tab --}}
    <script>
        function setActiveIndicator() {
            const activeTab = document.querySelector('.nav-tabs .nav-link.active');
            const navTabs = document.querySelector('.nav-tabs');

            if (activeTab && navTabs) {
                const tabRect = activeTab.getBoundingClientRect();
                const navRect = navTabs.getBoundingClientRect();

                const left = tabRect.left - navRect.left;
                const width = tabRect.width;

                navTabs.style.setProperty('--indicator-left', `${left}px`);
                navTabs.style.setProperty('--indicator-width', `${width}px`);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setActiveIndicator();
            window.addEventListener('resize', setActiveIndicator);

            const navLinks = document.querySelectorAll('.nav-tabs .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    const linkRect = this.getBoundingClientRect();
                    const navRect = this.closest('.nav-tabs').getBoundingClientRect();
                    const left = linkRect.left - navRect.left;
                    const width = linkRect.width;

                    const navTabs = this.closest('.nav-tabs');
                    navTabs.style.setProperty('--indicator-left', `${left}px`);
                    navTabs.style.setProperty('--indicator-width', `${width}px`);
                });
            });

            document.querySelector('.nav-tabs').addEventListener('mouseleave', function() {
                setActiveIndicator();
            });
        });
    </script>

    {{-- [TAMBAHAN] SweetAlert2 Script untuk Notifikasi Sukses --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#001931',
                timer: 3000
            });
        @endif
    </script>

</body>

</html>
