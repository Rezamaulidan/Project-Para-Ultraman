@extends('profil_pemilik')
@section('title', 'Informasi Data Staff - SIMK')

@section('content')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --navy-primary: #001931;
            --white: #ffffff;
            --grey-light: #f4f7f6;
            --accent-gold: #FFD700;
            --accent-success: #198754;
            /* Hijau untuk Aktif */
            --accent-danger: #dc3545;
            /* Merah untuk Non-Aktif */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--grey-light);
        }

        /* Container Utama */
        .staff-index-container {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Card Staff (untuk setiap individu) */
        .staff-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 25, 49, 0.05);
            padding: 25px;
            text-align: center;
            border: 2px solid transparent;
            /* Border default transparan */
            transition: all 0.3s ease-in-out;
            text-decoration: none;
            /* Menghilangkan underline dari <a> */
            display: block;
            /* Agar seluruh kartu bisa diklik */
        }

        /* Efek Interaktif (Lucu & Menarik) */
        .staff-card:hover {
            transform: translateY(-5px) scale(1.02);
            /* Sedikit melayang dan membesar */
            box-shadow: 0 15px 35px rgba(0, 25, 49, 0.15);
            border-color: var(--accent-gold);
            /* Border kuning saat hover */
            text-decoration: none;
        }

        /* Foto Profil */
        .staff-avatar-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 15px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--navy-primary);
            /* Navy border */
        }

        .staff-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Teks Utama */
        .staff-name-list {
            color: var(--navy-primary);
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 5px;
        }

        .staff-id-list {
            color: #6c757d;
            font-size: 0.85rem;
            display: block;
            margin-bottom: 15px;
        }

        /* Badge Informasi */
        .info-badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 15px;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* Shift */
        .shift-pagi-list {
            background-color: #fffde7;
            color: #f57f17;
        }

        .shift-sore-list {
            background-color: #e8eaf6;
            color: #3949ab;
        }

        /* Status */
        .status-aktif {
            background-color: #d1e7dd;
            color: var(--accent-success);
        }

        .status-cuti {
            background-color: #f8d7da;
            color: var(--accent-danger);
        }

        .header-section {
            position: relative;
            /* Jaga ini jika butuh positioning */
            display: flex;
            /* Ganti: menjadi justify-content-between di div HTML */
            margin-bottom: 2rem;
            padding: 1rem 0;
        }

        .header-section h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
            text-align: center;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }

        /* .header-section .btn {
        background-color: #001931;
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
    } */

        /* Header Halaman */
        .header-list {
            color: var(--navy-primary);
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 2rem;
            border-bottom: 3px solid var(--accent-gold);
            display: inline-block;
            padding-bottom: 5px;
        }

        .header-list i {
            color: var(--accent-gold);
            margin-right: 10px;
        }
    </style>

    <div class="staff-index-container">

        <div class="header-section d-flex justify-content-between align-items-center">
            <h2 class="header-list"><i class="fas fa-users-cog"></i> Daftar Staff Kos</h2>

            <div class="btn-group" role="group" aria-label="Staff Actions">
                <a href="{{ route('pemilik.shift.index') }}" class="btn btn-warning shadow-sm me-2 text-white">
                    <i class="fas fa-clock me-1"></i> Edit Jadwal Shift
                </a>

                <a href="{{ route('pemilik.registrasi_staff') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-user-plus me-1"></i> Input Staff Baru
                </a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            @forelse ($stafs as $staff)
                <div class="col">
                    <a href="{{ route('pemilik.informasi.staff', $staff->id_staf) }}" class="staff-card">
                        <div class="staff-avatar-wrapper">
                            @if ($staff->foto_staf)
                                <img src="{{ $storageUrl . $staff->foto_staf }}" alt="{{ $staff->nama_staf }}"
                                    class="staff-avatar">
                            @else
                                <img src="{{ asset('images/pp-default.jpg') }}" alt="Default" class="staff-avatar">
                            @endif
                        </div>
                        <span class="staff-name-list">{{ $staff->nama_staf }}</span>
                        <span class="staff-id-list">STF-{{ str_pad($staff->id_staf, 3, '0', STR_PAD_LEFT) }}</span>

                        <div class="d-flex justify-content-around mt-3">
                            @php
                                $shiftClass = $staff->jadwal == 'Pagi' ? 'shift-pagi-list' : 'shift-sore-list';
                                $shiftIcon = $staff->jadwal == 'Pagi' ? 'fas fa-sun' : 'fas fa-moon';
                            @endphp
                            <span class="info-badge {{ $shiftClass }}">
                                <i class="{{ $shiftIcon }}"></i> {{ $staff->jadwal }}
                            </span>

                            <span class="info-badge status-aktif">
                                Aktif
                            </span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Tidak ada data staff yang terdaftar saat ini.</p>
                    <a href="{{ route('pemilik.registrasi_staff') }}" class="btn btn-success"><i
                            class="fas fa-plus-circle me-1"></i> Tambah Staff Pertama</a>
                </div>
            @endforelse

        </div>
    </div>
@endsection
