<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Shift - SIMK</title>

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
        .card-absensi {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header-absensi {
            background: linear-gradient(135deg, #001931 0%, #003366 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        /* Tombol Absen */
        .btn-absen {
            background-color: #001931;
            color: white;
            font-weight: 700;
            padding: 0.8rem;
            border-radius: 12px;
            width: 100%;
            border: none;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-absen:hover {
            background-color: #003366;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 25, 49, 0.2);
        }

        /* Form Control Custom */
        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .form-select {
            border-left: none;
        }

        .form-select:focus {
            box-shadow: none;
            border-color: #ced4da;
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

            {{-- Logo Brand --}}
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-simk.png') }}" alt="Logo" style="width: 40px; height: auto;"
                    class="me-2">
                <span>SIMK <span class="fw-light">Staff</span></span>
            </a>

            {{-- Tombol Menu Utama --}}
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
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">

                <div class="card card-absensi">

                    {{-- Header Card --}}
                    <div class="card-header-absensi">
                        <h3 class="fw-bold mb-1">Absensi Staff</h3>
                        <p class="mb-0 opacity-75 small">
                            <i class="far fa-calendar-alt me-1"></i> {{ date('d F Y') }}
                        </p>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        {{-- 🛑 PERBAIKAN 1: TAMPILKAN ERROR VALIDASI (Jika Data Kurang/Salah) --}}
                        @if ($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4"
                                role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                                    <div>
                                        <strong>Mohon Periksa:</strong>
                                        <ul class="mb-0 ps-3 small">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Alert Error Biasa (Session) --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4"
                                role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle fs-4 me-3"></i>
                                    <div>
                                        <strong>Gagal!</strong> {{ session('error') }}
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Alert Sukses --}}
                        @if (session('sukses'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4"
                                role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fs-4 me-3"></i>
                                    <div>
                                        <strong>Berhasil!</strong> {{ session('sukses') }}
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('staff.absen.store') }}" method="POST">
                            @csrf

                            {{-- Input ID Staff (Dropdown) --}}
                            <div class="mb-4">
                                <label class="form-label">Nama Petugas / Staf</label>
                                <p class="text-muted small mb-2">Pilih nama Anda dari daftar di bawah ini:</p>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                                    {{-- 🛑 PERBAIKAN 2: Tambahkan Error Class pada Input --}}
                                    <select name="id_staf" required
                                        class="form-select @error('id_staf') is-invalid @enderror">
                                        <option value="" selected disabled>-- Cari Nama Anda --</option>
                                        @foreach ($daftarStaf as $staf)
                                            <option value="{{ $staf->id_staf }}">
                                                {{ $staf->nama_staf }} - Shift {{ ucfirst($staf->jadwal) }} - ID:
                                                {{ $staf->id_staf }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- 🛑 PERBAIKAN 3: Tampilkan Pesan Error di Bawah Input --}}
                                @error('id_staf')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text small mt-1">
                                    <i class="fas fa-info-circle me-1"></i> Pastikan ID sesuai jika ada nama yang sama.
                                </div>
                            </div>

                            {{-- Tombol Submit --}}
                            <button type="submit" class="btn btn-absen mt-3">
                                HADIR / PRESENSI <i class="fas fa-arrow-right ms-2"></i>
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
