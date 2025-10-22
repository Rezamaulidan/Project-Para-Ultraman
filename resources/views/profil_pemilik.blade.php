<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Manajemen Kos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style_home_pemilik.css') }}">

    @yield('styles')

</head>

<body>
    @include('navbar_pemilik')
    <div class="container-fluid main-content">
        @yield('content')
    </div>

    <div id="profileModal" class="modal">
        <div class="profile-card" id="profileCardContent">

            <div class="profile-header"></div>
            <div class="profile-photo-container" id="profilePhotoContainer">
                <img id="profileImage" src="" alt="Foto Profil">
                <img id="defaultImage" src="{{ asset('images/pp-default.jpg') }}" alt="Default Profile">

                <input type="file" id="fileInput" accept="image/*">
                <label for="fileInput" class="edit-photo-overlay">&#9998;</label>
            </div>

            <div class="profile-info-utama">
                <h1>Brilyanto Stevanus</h1>
                <p class="status">Pemilik Kos</p>
            </div>

            <div class="contact-details">
                <div class="detail-item">
                    <span class="icon">&#9993;</span> <span class="label">Username</span>
                    <span class="value">Bril1234</span>
                </div>
                <div class="detail-item">
                    <span class="icon">&#9742;</span> <span class="label">Nomor HP</span>
                    <span class="value">082118102912</span>
                </div>
                <div class="detail-item">
                    <span class="icon">&#127969;</span> <span class="label">Pengelola</span>
                    <span class="value">Kos Bril</span>
                </div>
            </div>

            <div class="profile-footer">
                <button class="cta-button" onclick="closeModal()">Tutup Profil</button>
                <button id="clearPhotoButton" class="clear-photo-button">Hapus Foto Profil</button>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/popup_pemilik.js') }}"></script>

    @yield('scripts')
</body>

</html>