<nav class="navbar" style="background-color: #0089FF;">
    <div class="container-fluid py-2">
        {{-- Logo --}}
        <a class="navbar-brand ms-3" href="#">
            <img src="{{ asset('img/gambar5.svg') }}" alt="Logo SIMK" style="width: 60px; height: auto;">
        </a>

        {{-- Tombol Register & Login --}}
        <div class="d-flex ms-auto me-4">
            <a href="{{ url('/pilihan-daftar') }}" class="btn btn-light me-2 fw-semibold">Register</a>
            <a href="#" class="btn btn-light fw-semibold">Login</a>
        </div>
    </div>
</nav>
