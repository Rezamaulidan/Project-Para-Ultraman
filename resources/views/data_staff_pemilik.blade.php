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
    display: flex;
    margin-bottom: 2rem;
    padding: 1rem 0;
}

.header-section h2 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
    text-align: center;
}

.header-section .btn {
    background-color: #001931;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
}

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

    <div class="header-section">
        <h2 class="header-list"><i class="fas fa-users-cog"></i> Daftar Staff Kos</h2>
        <a href="{{ route('pemilik.registrasi_staff') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Input Staff Baru
        </a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

        <div class="col">
            <a href="/info-detail-staff" class="staff-card">
                <div class="staff-avatar-wrapper">
                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Budi" class="staff-avatar">
                </div>
                <span class="staff-name-list">Budi Santoso</span>
                <span class="staff-id-list">STF-2024-001</span>
                <div class="d-flex justify-content-around mt-3">
                    <span class="info-badge shift-pagi-list">
                        <i class="fas fa-sun"></i> Pagi
                    </span>
                    <span class="info-badge status-aktif">
                        Aktif
                    </span>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="/info-detail-staff" class="staff-card">
                <div class="staff-avatar-wrapper">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29329?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cHJvZmlsZSUyMGZlbWFsZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"
                        alt="Siti" class="staff-avatar">
                </div>
                <span class="staff-name-list">Siti Rahayu</span>
                <span class="staff-id-list">STF-2024-002</span>
                <div class="d-flex justify-content-around mt-3">
                    <span class="info-badge shift-sore-list">
                        <i class="fas fa-moon"></i> Sore
                    </span>
                    <span class="info-badge status-cuti">
                        Cuti
                    </span>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="/info-detail-staff" class="staff-card">
                <div class="staff-avatar-wrapper">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60"
                        alt="Agus" class="staff-avatar">
                </div>
                <span class="staff-name-list">Agus Supriyadi</span>
                <span class="staff-id-list">STF-2024-003</span>
                <div class="d-flex justify-content-around mt-3">
                    <span class="info-badge shift-sore-list">
                        <i class="fas fa-moon"></i> Sore
                    </span>
                    <span class="info-badge status-aktif">
                        Aktif
                    </span>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="/info-detail-staff" class="staff-card">
                <div class="staff-avatar-wrapper">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60"
                        alt="Dewi" class="staff-avatar">
                </div>
                <span class="staff-name-list">Dewi Kartika</span>
                <span class="staff-id-list">STF-2024-004</span>
                <div class="d-flex justify-content-around mt-3">
                    <span class="info-badge shift-pagi-list">
                        <i class="fas fa-sun"></i> Pagi
                    </span>
                    <span class="info-badge status-aktif">
                        Aktif
                    </span>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection