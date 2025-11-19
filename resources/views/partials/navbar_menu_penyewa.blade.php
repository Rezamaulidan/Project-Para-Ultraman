<nav class="navbar navbar-dark" style="background-color: #0089FF;">
    <div class="container-fluid position-relative py-2">

        {{-- 1. Tombol Kembali (Panah Kiri) --}}
        {{-- Mengarah kembali ke Dashboard Penyewa --}}
        <a href="{{ route('penyewa.dashboard') }}" class="text-white position-absolute start-0 ms-4"
            style="top: 80%; transform: translateY(-50%); text-decoration: none;">
            <i class="fa-solid fa-chevron-left fa-2x"></i>
        </a>

        {{-- 2. Judul Halaman (Tengah) --}}
        <div class="w-100 text-center">
            <span class="navbar-brand mb-0 h1 fw-bold"></span>
        </div>

    </div>
</nav>
