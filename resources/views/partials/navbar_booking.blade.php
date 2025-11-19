{{-- 1. NAVBAR --}}
{{-- Navbar ini sekarang HANYA berisi Logo dan Tombol Hamburger --}}
<nav class="navbar navbar-dark" style="background-color: #0089FF;">
    <div class="container-fluid py-2">

        {{-- Logo (Link mengarah ke home) --}}
        <a class="navbar-brand ms-3" href="{{ route('home') }}">
            <img src="{{ asset('img/gambar5.svg') }}" alt="Logo SIMK" style="width: 60px; height: auto;">
        </a>

        {{-- Tombol Toggler (Hamburger) untuk memicu Offcanvas --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuOffcanvas"
            aria-controls="menuOffcanvas" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

    </div>
</nav>

{{-- 2. OFF CANVAS (MENU SLIDE-OUT) --}}
{{-- Ini adalah menu yang muncul dari samping saat hamburger di-klik --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="menuOffcanvas" aria-labelledby="menuOffcanvasLabel">

    {{-- Header Menu --}}
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menuOffcanvasLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Body Menu (Tempat tombol Keluar) --}}
    <div class="offcanvas-body">

        {{-- Tombol Keluar --}}
        {{-- Menggunakan list-group agar terlihat rapi seperti di screenshot Anda --}}
        <ul class="list-group list-group-flush">
            <li class.="list-group-item p-0">

                {{-- Form ini PENTING untuk logout yang aman (method POST) --}}
                <form action="{{ route('logout') }}" method="POST" class="d-grid">
                    @csrf

                    {{-- Ganti 'btn-light' dengan 'btn-danger' atau 'btn-outline-danger' jika ingin --}}
                    <button type="submit" class="btn btn-light text-start p-3 d-flex align-items-center">

                        {{-- Ganti dengan path ikon Anda. Ini adalah ikon placeholder --}}
                        <img src="{{ asset('img/ikon-keluar.png') }}" alt="Keluar" style="width: 24px; height: 24px;"
                            class="me-3">

                        <span class="fw-semibold fs-5">Keluar</span>
                    </button>
                </form>

            </li>

        </ul>

    </div>
</div>
