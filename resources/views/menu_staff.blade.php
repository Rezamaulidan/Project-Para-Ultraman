<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Staff - SIMK</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* [UBAH] Warna Header disamakan dengan navbar booking (#001931) */
    .header-staff {
        background-color: #001931;
        color: white;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .hero-section {
        background: linear-gradient(135deg, rgba(0, 25, 49, 0.1), rgba(0, 25, 49, 0.05));
        padding: 3rem 1rem;
        border-radius: 16px;
        margin-bottom: 2rem;
    }

    .menu-card {
        background: white;
        border-radius: 16px;
        padding: 2rem 1rem;
        text-align: center;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        border-color: #001931;
    }

    .menu-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        transition: transform 0.3s ease;
    }

    .menu-card:hover .menu-icon {
        transform: scale(1.1);
    }

    /* Warna Icon disesuaikan sedikit agar harmonis */
    .icon-blue {
        background: #e3f2fd;
        color: #0d47a1;
    }

    .icon-green {
        background: #e8f5e9;
        color: #1b5e20;
    }

    .icon-red {
        background: #ffebee;
        color: #b71c1c;
    }

    .icon-yellow {
        background: #fffde7;
        color: #f57f17;
    }

    .menu-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin-top: 0.5rem;
    }

    .logout-btn {
        background: white;
        color: #001931;
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: #e2e6ea;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .hero-section h2 {
            font-size: 2rem;
        }

        .menu-icon {
            width: 60px;
            height: 60px;
            font-size: 2rem;
        }
    }
    </style>
</head>

<body>

    {{-- Header --}}
    <header class="header-staff">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                {{-- Logo --}}
                <img src="{{ asset('img/logo-simk.png') }}" alt="Logo SIMK" class="img-fluid" style="height: 40px;">
                <div>
                    <h1 class="mb-0 h5">SIMK</h1>
                    <small class="d-none d-md-block">Sistem Informasi Manajemen Kos</small>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa fa-sign-out-alt me-2"></i>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    {{-- Hero Section --}}
    <div class="container mt-4">
        <div class="hero-section text-center">
            <p class="text-muted text-uppercase mb-2" style="letter-spacing: 2px;">Selamat Datang,</p>
            <h2 class="display-4 fw-bold text-dark mb-2">MENU STAFF</h2>
            <p class="text-muted">Pilih menu yang ingin Anda akses</p>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="container pb-5">
        <div class="row g-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('staff.manajemen') }}" class="menu-card">
                    <div class="menu-icon icon-blue"><i class="fa fa-users"></i></div>
                    <p class="menu-title">Manajemen Staff</p>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('staff.penyewa') }}" class="menu-card">
                    <div class="menu-icon icon-green"><i class="fa fa-user-circle"></i></div>
                    <p class="menu-title">Informasi Penyewa</p>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('staff.laporan_keamanan') }}" class="menu-card">
                    <div class="menu-icon icon-red"><i class="fa fa-shield-alt"></i></div>
                    <p class="menu-title">Laporan Keamanan</p>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('staff.shift_kerja') }}" class="menu-card">
                    <div class="menu-icon icon-yellow"><i class="fa fa-clock"></i></div>
                    <p class="menu-title">Shift Kerja</p>
                </a>
            </div>
        </div>
    </main>

    <footer class="text-center py-4 text-muted border-top">
        <small>&copy; {{ date('Y') }} SIMK - Sistem Informasi Manajemen Kos</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>