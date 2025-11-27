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

    /* Styling untuk Foto Profil */
    .profile-img-container {
        width: 120px;
        height: 120px;
        margin: -60px auto 10px auto;
        /* Posisi foto di atas header */
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .profile-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Tombol Edit/Hapus */
    .profile-actions {
        position: absolute;
        bottom: 0;
        right: 0;
        display: flex;
        gap: 5px;
    }

    .profile-actions .btn {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 50%;
        font-size: 0.9rem;
        line-height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Modifikasi CSS utama untuk mengakomodasi foto */
    .profile-header {
        height: 120px;
        /* Tinggikan header biru */
        background: linear-gradient(135deg, #012b52, #001931, #000000);
        border-radius: 15px 15px 0 0;
    }

    .profile-info-utama {
        text-align: center;
        margin-top: 5px;
        /* Kurangi jarak dari foto */
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
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
    @php
    // Ambil data PemilikKos
    $pemilik = auth()->user()->pemilikKos ?? null;
    // Tentukan URL foto atau foto default
    $fotoUrl = $pemilik && $pemilik->foto_profil
    ? Storage::url($pemilik->foto_profil)
    : asset('images/pp-default.jpg');
    // Definisikan path foto default (buat file ini di public/images)
    $defaultImgPath = asset('images/pp-default.jpg');
    @endphp

    <div id="profileModal" class="modal">
        <div class="profile-card" id="profileCardContent">

            <div class="profile-header"></div>

            {{-- FOTO PROFIL DAN AKSI --}}
            <div class="profile-img-container">
                <img id="profilePhoto" src="{{ $fotoUrl }}" alt="Foto Profil Pemilik">
                <div class="profile-actions">
                    {{-- Tombol Upload --}}
                    <button type="button" class="btn btn-primary" title="Ubah Foto"
                        onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    {{-- Tombol Hapus (Hanya muncul jika foto ada) --}}
                    <button type="button" class="btn btn-danger" title="Hapus Foto" id="deletePhotoButton"
                        style="{{ $pemilik && $pemilik->foto_profil ? '' : 'display: none;' }}" onclick="deletePhoto()">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            {{-- Input file tersembunyi untuk upload foto --}}
            <input type="file" id="photoInput" name="foto" accept="image/*" style="display: none;">
            {{-- END FOTO PROFIL DAN AKSI --}}

            <div class="profile-info-utama">
                <h1>{{ $pemilik->nama_pemilik ?? '-' }}</h1>
                <p class="status text-muted">Pemilik Kos</p>
            </div>

            {{-- ... (Sisa contact-details sama) ... --}}
            <div class="contact-details">
                <div class="detail-item">
                    <span class="icon text-primary">&#9993;</span>
                    <span class="label text-muted">Email</span>
                    <div class="value fw-bold">{{ $pemilik->email ?? '-' }}</div>
                </div>
                <div class="detail-item mt-3">
                    <span class="icon text-success">&#9742;</span>
                    <span class="label text-muted">Nomor HP</span>
                    <div class="value fw-bold">{{ $pemilik->no_hp ?? '-' }}</div>
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