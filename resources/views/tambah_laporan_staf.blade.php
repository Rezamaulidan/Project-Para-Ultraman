<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan Keamanan - SIMK</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-blue {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.15);
        }

        .form-control::placeholder {
            color: #9e9e9e;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .submit-button {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
            background: linear-gradient(135deg, #155bb5, #0a3d91);
        }

        .cancel-button {
            background-color: white;
            color: #1a73e8;
            border: 2px solid #1a73e8;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cancel-button:hover {
            background-color: #e8f4ff;
            color: #155bb5;
            border-color: #155bb5;
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

        .form-icon {
            color: #1a73e8;
            margin-right: 0.5rem;
        }

        .required {
            color: #dc3545;
            margin-left: 0.25rem;
        }

        @media (max-width: 576px) {
            .header-blue h1 {
                font-size: 1.25rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .submit-button,
            .cancel-button {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <header class="header-blue sticky-top">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('staff.laporan_keamanan') }}" class="back-button">
                <i class="fa fa-arrow-left fa-lg"></i>
            </a>
            <h1 class="mb-0 flex-grow-1 text-center me-4">Tambah Laporan Baru</h1>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">

                <form action="{{ route('staff.laporan_keamanan.store') }}" method="POST" class="form-container">
                    @csrf

                    {{-- Judul Laporan --}}
                    <div class="mb-4">
                        <label for="judul" class="form-label">
                            <i class="fa fa-file-alt form-icon"></i>
                            Judul / Subjek Insiden<span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="judul" name="judul"
                            placeholder="Contoh: Pemadaman Listrik di Lantai 5" required value="{{ old('judul') }}">
                        @error('judul')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal & Waktu --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="tanggal" class="form-label">
                                <i class="fa fa-calendar form-icon"></i>
                                Tanggal Insiden<span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required
                                value="{{ old('tanggal', date('Y-m-d')) }}">
                            @error('tanggal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="waktu" class="form-label">
                                <i class="fa fa-clock form-icon"></i>
                                Waktu Insiden<span class="required">*</span>
                            </label>
                            <input type="time" class="form-control" id="waktu" name="waktu" required
                                value="{{ old('waktu', date('H:i')) }}">
                            @error('waktu')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Jenis Kejadian --}}
                    <div class="mb-4">
                        <label for="jenis_kejadian" class="form-label">
                            <i class="fa fa-tag form-icon"></i>
                            Jenis Kejadian<span class="required">*</span>
                        </label>
                        <select class="form-select" id="jenis_kejadian" name="jenis_kejadian" required>
                            <option value="" selected disabled>Pilih jenis kejadian</option>
                            <option value="Orang mencurigakan"
                                {{ old('jenis_kejadian') == 'Orang mencurigakan' ? 'selected' : '' }}>Orang mencurigakan
                            </option>
                            <option value="Pemadaman listrik"
                                {{ old('jenis_kejadian') == 'Pemadaman listrik' ? 'selected' : '' }}>Pemadaman listrik
                            </option>
                            <option value="Kehilangan barang"
                                {{ old('jenis_kejadian') == 'Kehilangan barang' ? 'selected' : '' }}>Kehilangan barang
                            </option>
                            <option value="CCTV mati" {{ old('jenis_kejadian') == 'CCTV mati' ? 'selected' : '' }}>CCTV
                                mati</option>
                            <option value="Kerusakan fasilitas"
                                {{ old('jenis_kejadian') == 'Kerusakan fasilitas' ? 'selected' : '' }}>Kerusakan
                                fasilitas</option>
                            <option value="Lainnya" {{ old('jenis_kejadian') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                        @error('jenis_kejadian')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lokasi Kejadian --}}
                    <div class="mb-4">
                        <label for="lokasi" class="form-label">
                            <i class="fa fa-map-marker-alt form-icon"></i>
                            Lokasi Kejadian<span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                            placeholder="Contoh: Lantai 5, Depan Kamar 501" required value="{{ old('lokasi') }}">
                        @error('lokasi')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi Detail --}}
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">
                            <i class="fa fa-align-left form-icon"></i>
                            Deskripsi Detail Insiden<span class="required">*</span>
                        </label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6"
                            placeholder="Jelaskan secara rinci apa yang terjadi, siapa yang terlibat, dan tindakan apa yang telah diambil..."
                            required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-3 mt-4">
                        <a href="{{ route('staff.laporan_keamanan') }}" class="cancel-button text-center">
                            <i class="fa fa-times me-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="submit-button">
                            <i class="fa fa-paper-plane me-2"></i>
                            Kirim Laporan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Set default date and time to current
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('tanggal');
            const timeInput = document.getElementById('waktu');

            if (!dateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                dateInput.value = today;
            }

            if (!timeInput.value) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                timeInput.value = `${hours}:${minutes}`;
            }
        });
    </script>

</body>

</html>
