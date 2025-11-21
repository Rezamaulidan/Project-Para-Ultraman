<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keamanan - SIMK</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-blue {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .report-card {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-color: #1a73e8;
        }

        .add-button {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            border: none;
            border-radius: 50px;
            padding: 14px 40px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
            transition: all 0.3s ease;
        }

        .add-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 115, 232, 0.4);
            background: linear-gradient(135deg, #155bb5, #0a3d91);
        }

        .report-title {
            color: #1a73e8;
            font-weight: 600;
        }

        .report-date {
            color: #666;
            font-size: 0.875rem;
        }

        .report-preview {
            color: #757575;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .back-button {
            color: white;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .back-button:hover {
            opacity: 0.8;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9e9e9e;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 576px) {
            .header-blue h1 {
                font-size: 1.25rem;
            }

            .report-card {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <header class="header-blue sticky-top">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('staff.menu') }}" class="back-button">
                <i class="fa fa-arrow-left fa-lg"></i>
            </a>
            <h1 class="mb-0 flex-grow-1 text-center me-4">Laporan Keamanan</h1>
        </div>
    </header>

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">

                {{-- List Laporan --}}
                <div class="mb-4">
                    {{-- Report Card 1 --}}
                    <div class="report-card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="report-title mb-0">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                Orang mencurigakan
                            </h5>
                            <span class="report-date">19/05/2025 | 19:47</span>
                        </div>
                        <p class="report-preview mb-0">
                            Ditemukan tamu laki-laki yang masuk tanpa izin ke kamar penghuni...
                        </p>
                    </div>

                    {{-- Report Card 2 --}}
                    <div class="report-card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="report-title mb-0">
                                <i class="fa fa-bolt me-2"></i>
                                Pemadaman listrik
                            </h5>
                            <span class="report-date">11/05/2025 | 02:10</span>
                        </div>
                        <p class="report-preview mb-0">
                            Terjadi pemadaman listrik mendadak sekitar pukul 02.10...
                        </p>
                    </div>

                    {{-- Report Card 3 --}}
                    <div class="report-card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="report-title mb-0">
                                <i class="fa fa-box-open me-2"></i>
                                Kehilangan barang
                            </h5>
                            <span class="report-date">10/05/2025 | 07:00</span>
                        </div>
                        <p class="report-preview mb-0">
                            Penghuni kamar 13 melaporkan kehilangan sandal di depan kamar...
                        </p>
                    </div>

                    {{-- Report Card 4 --}}
                    <div class="report-card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="report-title mb-0">
                                <i class="fa fa-video-slash me-2"></i>
                                CCTV mati
                            </h5>
                            <span class="report-date">05/05/2025 | 09:05</span>
                        </div>
                        <p class="report-preview mb-0">
                            Salah satu CCTV di lorong lantai 2 mati sejak pukul 09.00...
                        </p>
                    </div>

                    {{-- Report Card 5 --}}
                    <div class="report-card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="report-title mb-0">
                                <i class="fa fa-box-open me-2"></i>
                                Kehilangan barang
                            </h5>
                            <span class="report-date">01/05/2025 | 07:00</span>
                        </div>
                        <p class="report-preview mb-0">
                            Penghuni kamar 7 melaporkan kehilangan helm yang diparkir di area motor...
                        </p>
                    </div>

                    {{-- Empty State (untuk saat data kosong) --}}
                    {{-- Uncomment ini jika ingin tampilkan saat data kosong --}}
                    {{-- <div class="empty-state">
                        <i class="fa fa-folder-open"></i>
                        <h5>Belum Ada Laporan</h5>
                        <p>Klik tombol "Tambah Laporan" untuk membuat laporan baru</p>
                    </div> --}}
                </div>

                {{-- Tombol Tambah Laporan --}}
                <div class="text-center mt-5 mb-4">
                    <a href="{{ route('staff.laporan_keamanan.create') }}" class="btn add-button">
                        <i class="fa fa-plus-circle me-2"></i>
                        Tambah Laporan
                    </a>
                </div>

            </div>
        </div>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
