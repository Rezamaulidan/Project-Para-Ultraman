<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Profil Staf - SIMK</title>

    {{-- Link CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Banner */
        .profile-header {
            background: linear-gradient(135deg, #001931 0%, #003366 100%);
            height: 160px;
            border-radius: 15px 15px 0 0;
        }

        /* Foto Profil */
        .profile-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            background-color: #fff;
            margin-top: -70px;
            /* Efek Overlap ke atas */
        }

        /* Info Box Styling */
        .info-box {
            background-color: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: #001931;
        }

        .icon-box {
            width: 45px;
            height: 45px;
            background-color: #f0f4f8;
            color: #001931;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .label-text {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .value-text {
            color: #212529;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .btn-navy {
            background-color: #001931;
            color: white;
            border: none;
        }

        .btn-navy:hover {
            background-color: #003366;
            color: white;
        }
    </style>
</head>

<body class="py-5">

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Header Navigasi --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-dark mb-0">Detail Profil Staf</h4>
                    <a href="{{ route('staff.manajemen') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>

                {{-- Alert Sukses --}}
                @if (session('sukses'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('sukses') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Card Profil Utama --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

                    {{-- Banner --}}
                    <div class="profile-header"></div>

                    <div class="card-body text-center px-4 pb-5">
                        {{-- Avatar --}}
                        <div class="d-flex justify-content-center mb-3">
                            @if ($staf->foto_staf)
                                <img src="{{ asset('storage/' . $staf->foto_staf) }}" alt="{{ $staf->nama_staf }}"
                                    class="profile-avatar">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($staf->nama_staf) }}&background=001931&color=fff&size=256&bold=true"
                                    alt="{{ $staf->nama_staf }}" class="profile-avatar">
                            @endif
                        </div>

                        {{-- Nama & Jabatan --}}
                        <h3 class="fw-bold text-dark mb-1">{{ $staf->nama_staf }}</h3>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            Staf Operasional
                        </span>

                        {{-- Tombol Edit (TANPA SYARAT USERNAME) --}}
                        {{-- Tombol ini sekarang akan selalu muncul --}}
                        <div class="mt-4">
                            <a href="{{ route('staff.manajemen.edit', ['id' => $staf->id_staf]) }}"
                                class="btn btn-navy rounded-pill px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-pen-to-square me-2"></i> Edit Profil Ini
                            </a>
                            <p class="text-muted small mt-2 fst-italic">
                                *Klik untuk mengubah data diri staf ini.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Grid Informasi Detail --}}
                <div class="row g-3">

                    {{-- Kolom 1: Email --}}
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="label-text">Alamat Email</div>
                                    <div class="value-text text-break">{{ $staf->email }}</div>
                                </div>
                                <div class="icon-box">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 2: No HP --}}
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="label-text">No WhatsApp</div>
                                    <div class="value-text">{{ $staf->no_hp }}</div>
                                </div>
                                <div class="icon-box">
                                    <i class="fab fa-whatsapp text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 3: Jadwal --}}
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="label-text">Shift Kerja</div>
                                    <div class="value-text text-primary">
                                        {{ ucfirst($staf->jadwal) }}
                                    </div>
                                </div>
                                <div class="icon-box bg-primary text-white">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI USERNAME SUDAH DIHAPUS DARI SINI --}}

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
