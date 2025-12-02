@php
    $user = Auth::user();

    // 1. Cek apakah Penghuni Sah (Lunas/Terlambat) -> Akses Penuh
    $isPenghuniSah = \App\Models\Booking::where('username', $user->username)
        ->whereIn('status_booking', ['lunas', 'terlambat'])
        ->exists();

    // 2. Cek apakah sedang dalam proses (Pending/Confirmed) -> Akses Terbatas
    $isProses = \App\Models\Booking::where('username', $user->username)
        ->whereIn('status_booking', ['pending', 'confirmed'])
        ->exists();
@endphp

{{-- 1. NAVBAR UTAMA (Header) --}}
<nav class="navbar navbar-dark sticky-top" style="background-color: #001931; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <div class="container">

        {{-- Logo Brand (Dikembalikan sesuai request) --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('penyewa.dashboard') }}">
            {{-- Menampilkan Logo Gambar --}}
            <img src="{{ asset('images/logo-simk.png') }}" alt="Logo SIMK" style="width: 50px; height: auto;">
        </a>

        {{-- Tombol Hamburger (Toggler) --}}
        <button class="navbar-toggler border-0 ms-auto" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#menuOffcanvas" aria-controls="menuOffcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>

    </div>
</nav>

{{-- 2. OFF CANVAS (MENU SLIDE-OUT DARI KANAN) --}}
<div class="offcanvas offcanvas-end border-0" tabindex="-1" id="menuOffcanvas" aria-labelledby="menuOffcanvasLabel">

    {{-- Header Menu --}}
    <div class="offcanvas-header bg-light">
        <h5 class="offcanvas-title fw-bold text-dark" id="menuOffcanvasLabel">
            <i class="fas fa-bars me-2"></i> Menu
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Body Menu --}}
    <div class="offcanvas-body p-0">

        {{-- PROFIL SINGKAT DI ATAS MENU --}}
        <div class="p-4 text-center bg-primary bg-opacity-10 border-bottom">
            <img src="{{ $user->penyewa->foto_profil ? asset('storage/' . $user->penyewa->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                class="rounded-circle shadow-sm mb-2"
                style="width: 70px; height: 70px; object-fit: cover; border: 3px solid white;">
            <h6 class="fw-bold mb-0 text-dark">{{ Str::limit($user->penyewa->nama_penyewa ?? $user->username, 20) }}
            </h6>
            <span class="badge bg-secondary rounded-pill mt-1">Penyewa</span>
        </div>

        <ul class="list-group list-group-flush">

            {{-- SKENARIO 1: SEDANG PROSES (PENDING/CONFIRMED) & BELUM SAH --}}
            {{-- Tampilkan HANYA LOGOUT (Dashboard & Profil disembunyikan sesuai request) --}}
            @if ($isProses && !$isPenghuniSah)

                <li class="list-group-item bg-light fw-bold text-muted small mt-2"></li>
                {{-- Hanya Menu Keluar yang tampil di bawah --}}
            @else
                {{-- SKENARIO 2: NORMAL (PENGHUNI SAH ATAU BELUM BOOKING) --}}

                {{-- MENU UTAMA --}}
                <li class="list-group-item bg-light fw-bold text-muted small mt-2">NAVIGASI UTAMA</li>

                {{-- 1. Dashboard --}}
                <a href="{{ route('penyewa.dashboard') }}"
                    class="list-group-item list-group-item-action py-3 {{ Request::routeIs('penyewa.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-columns me-3 fa-fw"></i> Dashboard
                </a>

                {{-- 2. Cari Kamar (Hanya jika belum punya kamar) --}}
                @if (!$isPenghuniSah)
                    <a href="{{ route('dashboard.booking') }}"
                        class="list-group-item list-group-item-action py-3 {{ Request::routeIs('dashboard.booking') ? 'active' : '' }}">
                        <i class="fas fa-search me-3 fa-fw"></i> Cari Kamar Kos
                    </a>
                @endif

                {{-- 3. Menu Penghuni (Hanya jika Sah) --}}
                @if ($isPenghuniSah)
                    <a href="{{ route('penyewa.kamar') }}"
                        class="list-group-item list-group-item-action py-3 {{ Request::routeIs('penyewa.kamar') ? 'active' : '' }}">
                        <i class="fas fa-bed me-3 fa-fw text-primary"></i> Kamar Saya
                    </a>

                    <a href="{{ route('penyewa.pembayaran') }}"
                        class="list-group-item list-group-item-action py-3 {{ Request::routeIs('penyewa.pembayaran') ? 'active' : '' }}">
                        <i class="fas fa-history me-3 fa-fw text-success"></i> Menu Pembayaran
                    </a>

                    <a href="{{ route('penyewa.keamanan') }}"
                        class="list-group-item list-group-item-action py-3 {{ Request::routeIs('penyewa.keamanan') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt me-3 fa-fw text-danger"></i> Laporan Keamanan
                    </a>
                @endif

                {{-- PENGATURAN --}}
                <li class="list-group-item bg-light fw-bold text-muted small mt-2">PENGATURAN</li>

                <a href="{{ route('penyewa.informasi') }}"
                    class="list-group-item list-group-item-action py-3 {{ Request::routeIs('penyewa.informasi') ? 'active' : '' }}">
                    <i class="fas fa-user-cog me-3 fa-fw"></i> Edit Profil
                </a>

            @endif

            {{-- TOMBOL KELUAR (SELALU MUNCUL) --}}
            <li class="list-group-item p-3 mt-2 border-top">
                <form action="{{ route('logout') }}" method="POST" class="d-grid">
                    @csrf
                    <button type="submit"
                        class="btn btn-outline-danger d-flex align-items-center justify-content-center py-2">
                        <i class="fas fa-sign-out-alt me-2"></i> Keluar
                    </button>
                </form>
            </li>

        </ul>
    </div>
</div>
