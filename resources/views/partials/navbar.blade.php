<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #001931;">
    <div class="container-fluid py-2">

        {{-- Logo (Link mengarah ke home) --}}
        <a class="navbar-brand ms-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-simk.png') }}" alt="Logo SIMK" style="width: 60px; height: auto;">
        </a>

        {{-- Tombol Toggler (Hamburger) untuk mobile view --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Konten Navbar yang akan disembunyikan di mobile --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            {{-- Tombol Register & Login dipindahkan ke sini dan didorong ke kanan dengan ms-auto --}}
            <div class="d-flex ms-auto me-4">
                <a href="{{ route('register.pilihan') }}" class="btn btn-light me-2 fw-semibold">Register</a>
                <a href="{{ route('login') }}" class="btn btn-light fw-semibold">Login</a>
            </div>
        </div>

    </div>
</nav>
