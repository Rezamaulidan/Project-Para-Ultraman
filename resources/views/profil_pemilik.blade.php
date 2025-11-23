<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Informasi Manajemen Kos')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style_home_pemilik.css') }}">

    @yield('styles')

    <style>
        /* --- HEADER PROFIL --- */
        .profile-header {
            height: 100px;
            background: linear-gradient(135deg, #012b52, #001931, #000000);
            border-radius: 15px 15px 0 0;
            position: relative;
            margin-bottom: 60px;
            display: block;
            overflow: visible;
        }

        /* --- WRAPPER FOTO --- */
        .custom-photo-wrapper {
            width: 110px;
            height: 110px;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translate(-50%, 50%);
            z-index: 20;
        }

        /* Lingkaran Foto */
        .custom-photo-container {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #fff;
            border: 5px solid #fff;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .custom-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Inisial Huruf */
        .avatar-initials {
            width: 100%;
            height: 100%;
            background-color: #e9ecef;
            color: #001931;
            font-size: 3rem;
            font-weight: 700;
            display: flex;
            justify-content: center;
            align-items: center;
            text-transform: uppercase;
        }

        /* --- TOMBOL KAMERA (UPLOAD) --- */
        .custom-btn-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            cursor: pointer;
            border: 3px solid #fff;
            transition: transform 0.2s;
            z-index: 30;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .custom-btn-upload:hover {
            background-color: #0b5ed7;
            transform: scale(1.1);
        }

        /* --- INFO UTAMA --- */
        .profile-info-utama {
            text-align: center;
            margin-top: 60px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .profile-info-utama h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        /* --- DETAIL KONTAK --- */
        .contact-details {
            padding: 20px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .icon-wrap {
            font-size: 1.2rem;
            margin-right: 15px;
            width: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .detail-content {
            flex-grow: 1;
        }

        /* --- FOOTER & TOMBOL --- */
        .profile-footer {
            padding: 0 20px 30px;
            border-top: none;
            display: flex;
            flex-direction: column;
            /* Susun Vertikal */
            align-items: center;
            /* Rata Tengah */
            gap: 10px;
            /* Jarak antar tombol */
        }

        /* Tombol Tutup (Navy) */
        .btn-tutup-profil {
            background-color: #001931 !important;
            border-color: #001931 !important;
            color: white !important;
            padding: 10px 40px !important;
            border-radius: 50px !important;
            font-weight: 500 !important;
            font-size: 1rem !important;
            width: 80%;
            /* Supaya tombolnya lebar */
            transition: all 0.3s ease;
        }

        .btn-tutup-profil:hover {
            background-color: #033f7e !important;
            transform: translateY(-2px);
        }

        /* Tombol Hapus (Merah Outline) */
        .btn-hapus-foto {
            background-color: transparent !important;
            border: 1px solid #dc3545 !important;
            color: #dc3545 !important;
            padding: 8px 40px !important;
            border-radius: 50px !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            width: 80%;
            /* Lebar sama dengan tombol tutup */
            transition: all 0.3s ease;
        }

        .btn-hapus-foto:hover {
            background-color: #dc3545 !important;
            color: white !important;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 25;
        }

        .swal2-container {
            z-index: 9999 !important;
        }
    </style>
</head>

<body>
    @include('navbar_pemilik')

    <div class="container-fluid main-content">
        @yield('content')
    </div>

    {{-- MODAL PROFIL --}}
    @if (auth()->check())
        <div id="profileModal" class="modal">
            <div class="profile-card" id="profileCardContent">

                {{-- HEADER --}}
                <div class="profile-header">
                    {{-- WRAPPER FOTO --}}
                    <div class="custom-photo-wrapper">
                        {{-- Lingkaran Foto --}}
                        <div class="custom-photo-container">
                            <div class="loading-overlay" id="photoLoading">
                                <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                            </div>

                            @php
                                $hasPhoto =
                                    auth()->user()->pemilikKos->foto_profil &&
                                    file_exists(public_path('storage/' . auth()->user()->pemilikKos->foto_profil));
                                $initial = substr(auth()->user()->pemilikKos->nama_pemilik ?? 'U', 0, 1);
                            @endphp

                            <img id="profileImage"
                                src="{{ $hasPhoto ? asset('storage/' . auth()->user()->pemilikKos->foto_profil) : '' }}"
                                alt="Foto Profil" style="{{ $hasPhoto ? 'display: block;' : 'display: none;' }}"
                                onerror="this.style.display='none'; document.getElementById('defaultAvatar').style.display='flex';">

                            <div id="defaultAvatar" class="avatar-initials"
                                style="{{ $hasPhoto ? 'display: none;' : 'display: flex;' }}">
                                {{ $initial }}
                            </div>
                        </div>

                        {{-- Tombol Upload (Ikon Kamera) --}}
                        <label for="fileInput" class="custom-btn-upload" title="Ganti Foto">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="fileInput" accept="image/*" style="display: none;"
                            onchange="uploadPhoto()">

                        {{-- Tombol Hapus (Ikon) SUDAH DIHAPUS DARI SINI --}}
                    </div>
                </div>

                {{-- INFO UTAMA --}}
                <div class="profile-info-utama">
                    <h1>{{ auth()->user()->pemilikKos->nama_pemilik ?? 'Nama Pemilik' }}</h1>
                    <p class="status text-muted">Pemilik Kos</p>
                </div>

                {{-- DETAIL KONTAK --}}
                <div class="contact-details">
                    {{-- Email --}}
                    <div class="detail-row">
                        <div class="icon-wrap text-primary">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="detail-content">
                            <span class="label text-muted d-block small">Email</span>
                            <div class="value fw-bold">{{ auth()->user()->pemilikKos->email ?? '-' }}</div>
                        </div>
                    </div>
                    {{-- HP --}}
                    <div class="detail-row">
                        <div class="icon-wrap text-success">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="detail-content">
                            <span class="label text-muted d-block small">Nomor HP</span>
                            <div class="value fw-bold">{{ auth()->user()->pemilikKos->no_hp ?? '-' }}</div>
                        </div>
                    </div>
                    {{-- Username --}}
                    <div class="detail-row">
                        <div class="icon-wrap text-warning">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="detail-content">
                            <span class="label text-muted d-block small">Username</span>
                            <div class="value fw-bold">{{ auth()->user()->username }}</div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER (Tombol Hapus dan Tutup) --}}
                <div class="profile-footer text-center pb-4">

                    {{-- Tombol Hapus Foto (Hanya muncul jika ada foto) --}}
                    @if ($hasPhoto)
                        <button type="button" class="btn btn-hapus-foto" onclick="deletePhoto()">
                            <i class="fas fa-trash me-2"></i> Hapus Foto
                        </button>
                    @endif

                    {{-- Tombol Tutup --}}
                    <button type="button" class="btn btn-tutup-profil" onclick="closeModal()">Tutup Profil</button>
                </div>

            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/popup_pemilik.js') }}"></script>

    @yield('scripts')
</body>

</html>
