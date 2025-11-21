<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Menambahkan CSRF Token untuk keamanan form/AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Informasi Manajemen Kos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style_home_pemilik.css') }}">

    @yield('styles')

    {{-- Tambahan CSS khusus agar tampilan tetap rapi tanpa foto --}}
    <style>
        /* Menyesuaikan header profil karena foto dihapus */
        .profile-header {
            height: 80px;
            /* Mengurangi tinggi header biru */
            background: linear-gradient(135deg, #012b52, #001931, #000000);
            border-radius: 15px 15px 0 0;
        }

        /* Mengatur ulang posisi info utama agar naik ke atas */
        .profile-info-utama {
            text-align: center;
            margin-top: 20px;
            /* Memberi jarak dari header */
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .profile-info-utama h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .contact-details {
            padding: 20px;
        }

        .btn-tutup-profil {
            background-color: #001931 !important;
            border-color: #0d6efd !important;
            color: white !important;
            padding: 10px 40px !important;
            /* Memastikan ukuran tombol pas */
            border-radius: 50px !important;
            /* Memastikan bentuk kapsul */
            font-weight: 500 !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            width: auto !important;
            height: auto !important;
            display: inline-block !important;
            transition: all 0.3s ease;
        }

        .btn-tutup-profil:hover {
            background-color: #063d90 !important;
            transform: translateY(-2px);
        }
    </style>

</head>

<body>
    @include('navbar_pemilik')
    <div class="container-fluid main-content">
        @yield('content')
    </div>

    {{-- Hanya tampilkan modal jika pemilik kos sudah login --}}
    @if (auth()->check())
        <div id="profileModal" class="modal">
            <div class="profile-card" id="profileCardContent">

                <div class="profile-header"></div>

                <div class="profile-info-utama">
                    {{-- Tampilkan nama pemilik dari database --}}
                    <h1>{{ auth()->user()->pemilikKos->nama_pemilik ?? '-' }}</h1>
                    <p class="status text-muted">Pemilik Kos</p>
                </div>

                <div class="contact-details">
                    <div class="detail-item">
                        <span class="icon text-primary">&#9993;</span>
                        <span class="label text-muted">Email</span>
                        <div class="value fw-bold">{{ auth()->user()->pemilikKos->email ?? '-' }}</div>
                    </div>
                    <div class="detail-item mt-3">
                        <span class="icon text-success">&#9742;</span>
                        <span class="label text-muted">Nomor HP</span>
                        <div class="value fw-bold">{{ auth()->user()->pemilikKos->no_hp ?? '-' }}</div>
                    </div>
                    <div class="detail-item mt-3">
                        <span class="icon text-warning">&#127969;</span>
                        <span class="label text-muted">Username</span>
                        <div class="value fw-bold">{{ auth()->user()->username }}</div>
                    </div>
                </div>

                <div class="profile-footer text-center pb-4">
                    <button type="button" class="btn btn-tutup-profil" onclick="closeModal()">Tutup Profil</button>
                </div>

            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/popup_pemilik.js') }}"></script>

    @yield('scripts')
</body>

</html>
