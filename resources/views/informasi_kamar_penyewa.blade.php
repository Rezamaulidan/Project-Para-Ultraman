<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Kamar - SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Link Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* --- Header --- */
        .header-penyewa {
            background-color: #001931;
            color: white;
            padding: 1rem;
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


        /* --- Styling Konten Kamar --- */
        .card-base {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            height: 100%;
        }

        .gold-star { color: #ffc107; margin: 0 5px; }

        .kamar-photo-frame {
            width: 100%;
            height: 300px;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kamar-photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .kamar-photo-frame img:hover {
            transform: scale(1.02);
        }

        .modular-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .modular-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 10px;
        }

        .badge-status {
            display: inline-block;
            padding: 5px 15px;
            background-color: #e8f5e9;
            color: #2e7d32;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .price-text { color: #0089FF; }

        .info-table { width: 100%; }
        .info-table tr td {
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }
        .info-table tr:last-child td { border-bottom: none; }

        .icon-col { width: 30px; color: #0089FF; }
        .label-col { width: 120px; font-weight: 600; color: #6c757d; }
        .value-col { font-weight: 500; color: #333; text-align: right; }

        .fasilitas-badge {
            background-color: #f8f9fa;
            color: #495057;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            display: inline-block;
            margin: 2px;
            border: 1px solid #e9ecef;
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

    {{-- 3. Tab Navigasi (Konsisten dengan Informasi Keamanan) --}}
    <div class="container-fluid bg-light pt-2">
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.informasi') }}">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    Informasi Penyewa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.keamanan') }}">
                    <i class="fa-solid fa-shield-halved me-1"></i>
                    Informasi Keamanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('penyewa.pembayaran') }}">
                    <i class="fa-solid fa-money-check-dollar me-1"></i>
                    Menu Pembayaran
                </a>
            </li>
            <li class="nav-item">
                {{-- Tab Aktif --}}
                <a class="nav-link active" aria-current="page" href="{{ route('penyewa.kamar') }}">
                    <i class="fa-solid fa-box me-1"></i>
                    Informasi Kamar
                </a>
            </li>
        </ul>
    </div>

    {{-- 4. Konten Utama --}}
    <div class="container-fluid p-4">
        <div class="row justify-content-center g-4">

            {{-- KOLOM KIRI: Foto Kamar --}}
            <div class="col-12 col-lg-5">
                <div class="card-base p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1">
                            <i class="fas fa-star gold-star"></i>
                            Kamar No. {{ $kamar->no_kamar ?? '-' }}
                            <i class="fas fa-star gold-star"></i>
                        </h4>
                        <p class="text-muted small">Kamar Anda Saat Ini</p>
                    </div>

                    <div class="kamar-photo-frame mb-3">
                        @if ($kamar->foto_kamar && file_exists(public_path($kamar->foto_kamar)))
                            <img src="{{ asset($kamar->foto_kamar) }}" alt="Foto Kamar {{ $kamar->no_kamar }}">
                        @else
                            <div class="text-center text-muted">
                                <i class="fa-regular fa-image fa-3x mb-2"></i>
                                <p class="mb-0 small">Tidak ada foto kamar</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Detail Utama --}}
            <div class="col-12 col-lg-7">
                <div class="card-base p-4">

                    {{-- 2. Tabel Informasi Umum --}}
                    <div class="modular-card">
                        <h3 class="modular-card-title">
                            <i class="fas fa-info-circle text-primary me-2"></i> Spesifikasi Kamar
                        </h3>
                        <table class="info-table">
                            <tr>
                                <td class="icon-col"><i class="fas fa-hashtag"></i></td>
                                <td class="label-col">Nomor Kamar</td>
                                <td class="value-col text-primary fw-bold">{{ $kamar->no_kamar ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="icon-col"><i class="fas fa-layer-group"></i></td>
                                <td class="label-col">Posisi Lantai</td>
                                <td class="value-col">{{ $kamar->lantai ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="icon-col"><i class="fas fa-bed"></i></td>
                                <td class="label-col">Tipe Kamar</td>
                                <td class="value-col">{{ ucfirst($kamar->tipe_kamar ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="icon-col"><i class="fas fa-ruler-combined"></i></td>
                                <td class="label-col">Ukuran Luas</td>
                                <td class="value-col">{{ $kamar->ukuran ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- 3. Fasilitas --}}
                    <div class="modular-card mb-0">
                        <h3 class="modular-card-title">
                            <i class="fas fa-list-check text-primary me-2"></i> Fasilitas Termasuk
                        </h3>
                        <div class="fasilitas-content">
                            @if(!empty($kamar->fasilitas))
                                @foreach(explode(',', $kamar->fasilitas) as $item)
                                    <span class="fasilitas-badge">
                                        <i class="fas fa-check text-success me-1 small"></i> {{ trim($item) }}
                                    </span>
                                @endforeach
                            @else
                                <p class="text-muted fst-italic mb-0">Data fasilitas belum diinput.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Sliding Indicator (Sama persis dengan Informasi Keamanan) --}}
    <script>
        // Fungsi untuk mengatur posisi sliding indicator
        function setActiveIndicator() {
            const activeTab = document.querySelector('.nav-tabs .nav-link.active');
            const navTabs = document.querySelector('.nav-tabs');

            if (activeTab && navTabs) {
                const tabRect = activeTab.getBoundingClientRect();
                const navRect = navTabs.getBoundingClientRect();

                // Hitung posisi relatif terhadap nav-tabs
                const left = tabRect.left - navRect.left;
                const width = tabRect.width;

                // Set properti CSS untuk indicator
                navTabs.style.setProperty('--indicator-left', `${left}px`);
                navTabs.style.setProperty('--indicator-width', `${width}px`);
            }
        }

        // Jalankan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            setActiveIndicator();

            // Update indicator saat window diresize
            window.addEventListener('resize', setActiveIndicator);

            // Tambahkan hover effect
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

            // Kembalikan ke posisi active saat mouse leave
            document.querySelector('.nav-tabs').addEventListener('mouseleave', function() {
                setActiveIndicator();
            });
        });
    </script>
</body>

</html>
