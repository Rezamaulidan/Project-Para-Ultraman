<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking Kamar - SIMK</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Biru */
        .header-booking {
            background: linear-gradient(135deg, #001931, #003366);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Card Form */
        .booking-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header-custom {
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px;
        }

        /* Info Kamar (Kiri) */
        .room-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #e9ecef;
        }

        .room-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .price-tag {
            color: #007bff;
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* Input Styling */
        .form-label {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
        }

        /* Readonly Input (Data Penyewa) */
        .input-readonly {
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            color: #6c757d;
            font-weight: 500;
        }

        /* Total Price Box */
        .total-box {
            background-color: #e7f5ff;
            border: 1px solid #b3d7ff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .total-label {
            font-size: 0.9rem;
            color: #0056b3;
            font-weight: 600;
        }

        .total-value {
            font-size: 1.5rem;
            color: #007bff;
            font-weight: 800;
        }

        /* Submit Button */
        .btn-booking {
            background-color: #001931;
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-booking:hover {
            background-color: #003366;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 25, 49, 0.2);
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <header class="header-booking mb-4">
        <div class="container d-flex align-items-center">
            <a href="{{ url()->previous() }}" class="text-white text-decoration-none me-3">
                <i class="fas fa-arrow-left fa-lg"></i>
            </a>
            <h4 class="mb-0 fw-bold">Formulir Booking Kamar</h4>
        </div>
    </header>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <form action="{{ route('penyewa.booking.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Hidden Input untuk No Kamar --}}
                    <input type="hidden" name="no_kamar" value="{{ $kamar->no_kamar }}">
                    {{-- Harga Dasar untuk JS --}}
                    <input type="hidden" id="hargaDasar" value="{{ $kamar->harga }}">

                    <div class="row g-4">

                        {{-- KOLOM KIRI: Ringkasan Kamar --}}
                        <div class="col-md-4">
                            <div class="room-summary sticky-top" style="top: 20px;">
                                <h5 class="fw-bold mb-3">Ringkasan Kamar</h5>

                                <img src="{{ $kamar->foto_kamar ? asset($kamar->foto_kamar) : asset('img/kamar-atas.png') }}"
                                    class="room-image shadow-sm" alt="Foto Kamar">

                                <div class="mb-3">
                                    <h6 class="fw-bold">Kamar No. {{ $kamar->no_kamar }}</h6>
                                    <span class="badge bg-info text-dark mb-2">{{ ucfirst($kamar->tipe_kamar) }}</span>
                                    <div class="price-tag">Rp {{ number_format($kamar->harga, 0, ',', '.') }} <span
                                            class="small text-muted fw-normal">/ bulan</span></div>
                                </div>

                                <hr>

                                <div class="small text-muted">
                                    <i class="fas fa-ruler-combined me-2"></i> Ukuran: {{ $kamar->ukuran }} <br>
                                    <i class="fas fa-layer-group me-2"></i> Lantai: {{ $kamar->lantai }}
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: Form Input --}}
                        <div class="col-md-8">
                            <div class="card booking-card">
                                <div class="card-header-custom">
                                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-clipboard-list me-2"></i>
                                        Lengkapi Data Pemesanan</h5>
                                </div>
                                <div class="card-body">

                                    {{-- 1. Data Penyewa (Otomatis) --}}
                                    <h6 class="fw-bold text-primary mb-3">Data Penyewa</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control input-readonly"
                                                value="{{ $penyewa->nama_penyewa }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nomor HP (WhatsApp)</label>
                                            <input type="text" class="form-control input-readonly"
                                                value="{{ $penyewa->no_hp }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <input type="text" class="form-control input-readonly"
                                                value="{{ $penyewa->jenis_kelamin }}" readonly>
                                        </div>
                                    </div>

                                    <hr class="mb-4">

                                    {{-- 2. Detail Sewa --}}
                                    <h6 class="fw-bold text-primary mb-3">Detail Sewa</h6>

                                    {{-- Tanggal Mulai --}}
                                    <div class="mb-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai Sewa <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                            id="tanggal_mulai" name="tanggal_mulai" required min="{{ date('Y-m-d') }}">
                                        @error('tanggal_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Durasi Sewa --}}
                                    <div class="mb-3">
                                        <label for="durasi_sewa" class="form-label">Durasi Sewa (Bulan) <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('durasi_sewa') is-invalid @enderror"
                                            id="durasi_sewa" name="durasi_sewa" required onchange="hitungTotal()">
                                            <option value="1" selected>1 Bulan</option>
                                            <option value="3">3 Bulan</option>
                                            <option value="6">6 Bulan</option>
                                            <option value="12">12 Bulan (1 Tahun)</option>
                                        </select>
                                    </div>

                                    {{-- Upload KTP --}}
                                    <div class="mb-4">
                                        <label for="foto_ktp" class="form-label">Upload Foto KTP (Identitas) <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('foto_ktp') is-invalid @enderror" id="foto_ktp"
                                            name="foto_ktp" accept="image/*" required>
                                        <div class="form-text">Format: JPG, PNG. Maksimal 2MB. Data Anda aman.</div>
                                        @error('foto_ktp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Total Harga --}}
                                    <div class="total-box mb-4">
                                        <div class="total-label">Total Pembayaran Awal</div>
                                        <div class="total-value" id="tampilanTotal">Rp
                                            {{ number_format($kamar->harga, 0, ',', '.') }}</div>
                                    </div>

                                    {{-- Tombol Submit --}}
                                    <button type="submit" class="btn btn-booking w-100 btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i> Ajukan Booking Sekarang
                                    </button>

                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Script Hitung Total Otomatis --}}
    <script>
        function hitungTotal() {
            const hargaDasar = document.getElementById('hargaDasar').value;
            const durasi = document.getElementById('durasi_sewa').value;

            const total = hargaDasar * durasi;

            // Format Rupiah sederhana
            const formattedTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(total);

            document.getElementById('tampilanTotal').innerText = formattedTotal;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
