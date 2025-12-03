<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penyewa - Staff View</title>

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

        /* === TABLE CARD STYLING === */
        .table-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background: white;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #6c757d;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1.2rem 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .table tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            color: #333;
            font-size: 0.95rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: #f9fbff;
        }

        /* Avatar */
        .avatar-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #e0e7ff;
            color: #001931;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            margin-right: 15px;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Badge Status */
        .badge-status {
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .badge-active {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
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

    {{-- 1. NAVBAR (SEDERHANA) --}}
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

        {{-- Header & Filter --}}
        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark mb-1">Data Informasi Penyewa</h2>
                <p class="text-muted mb-0">Kelola dan pantau data penyewa yang terdaftar di sistem.</p>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center ps-4" width="5%">No</th>
                                <th width="30%">Nama Penyewa</th>
                                <th width="20%">Kontak</th>
                                <th width="25%">Email</th>
                                <th class="text-center pe-4" width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($daftar_penyewa as $index => $penyewa)
                                <tr>
                                    {{-- No --}}
                                    <td class="text-center text-muted ps-4">{{ $index + 1 }}</td>

                                    {{-- Nama & Avatar --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- Avatar Inisial / Foto --}}
                                            @if ($penyewa->foto_profil)
                                                <img src="{{ asset('storage/' . $penyewa->foto_profil) }}"
                                                    class="avatar-circle">
                                            @else
                                                <div class="avatar-circle">
                                                    {{-- Menggunakan nama_penyewa dari tabel penyewa --}}
                                                    {{ strtoupper(substr($penyewa->nama_penyewa ?? $penyewa->username, 0, 1)) }}
                                                </div>
                                            @endif

                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                    {{ $penyewa->nama_penyewa ?? $penyewa->username }}
                                                </div>
                                                <span
                                                    class="badge bg-light text-secondary border rounded-pill fw-normal mt-1">
                                                    ID: {{ $penyewa->username }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kontak --}}
                                    <td>
                                        @if ($penyewa->no_hp)
                                            <a href="https://wa.me/{{ substr($penyewa->no_hp, 0, 1) == '0' ? '62' . substr($penyewa->no_hp, 1) : $penyewa->no_hp }}"
                                                target="_blank"
                                                class="text-decoration-none text-success fw-bold btn btn-sm btn-light rounded-pill border px-3">
                                                <i class="fab fa-whatsapp me-1 text-success fa-lg"></i>
                                                {{ $penyewa->no_hp }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic small">Tidak ada kontak</span>
                                        @endif
                                    </td>

                                    {{-- Email --}}
                                    <td class="text-secondary fw-medium">{{ $penyewa->email ?? '-' }}</td>

                                    {{-- Status --}}
                                    <td class="text-center pe-4">
                                        <span class="badge-status badge-active">
                                            <i class="fas fa-check-circle me-1"></i> Terdaftar
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted opacity-50">
                                            <i class="fas fa-folder-open fa-4x mb-3"></i>
                                            <p class="mb-0 fs-5">Belum ada data penyewa</p>
                                            <small>Data penyewa yang mendaftar akan muncul di sini.</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Footer Info --}}
        <div class="d-flex justify-content-between align-items-center mt-3 px-2">
            <small class="text-muted">Menampilkan seluruh data penyewa.</small>
            <div class="text-muted small">
                Total: <strong>{{ $daftar_penyewa->count() }}</strong> Penyewa
            </div>
        </div>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
