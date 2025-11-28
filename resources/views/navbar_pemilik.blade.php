<!-- resources/views/partials/navbar_pemilik.blade.php -->

<div class="header-container">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('pemilik.home') }}">
                <img src="{{ asset('images/logo-simk.png') }}" alt="Logo SIMK" class="me-3" style="height: 40px;">
                Sistem Informasi Manajemen Kos
            </a>

            <a href="#" class="text-white" onclick="openModal(); return false;">
                <i class="fa-solid fa-user-circle fa-2x"></i>
            </a>
        </div>

        <nav class="navbar navbar-expand-lg main-nav-menu p-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav d-flex justify-content-between w-100">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pemilik.home') ? 'active' : '' }} text-center"
                            aria-current="page" href="{{ route('pemilik.home') }}">
                            <i class="fa-solid fa-house me-1"></i> Beranda
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-center {{ request()->routeIs('pemilik.datakamar', 'pemilik.datapenyewa', 'pemilik.datastaff') ? 'active' : '' }}"
                            href="#" id="infoDataDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-circle-info me-1"></i> Informasi Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="infoDataDropdown">
                            <li><a class="dropdown-item" href="{{ route('pemilik.datakamar') }}">Kamar</a></li>
                            <li><a class="dropdown-item" href="{{ route('pemilik.datapenyewa') }}">Penyewa</a></li>
                            <li><a class="dropdown-item" href="{{ route('pemilik.datastaff') }}">Staff</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pemilik.transaksi') ? 'active' : '' }} text-center"
                            aria-current="page" href="{{ route('pemilik.transaksi') }}">
                            <i class="fa-solid fa-receipt me-1"></i> Transaksi
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-center {{ request()->routeIs('pemilik.pengeluaran', 'pemilik.keamanan') ? 'active' : '' }}"
                            href="#" id="laporanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-chart-line me-1"></i> Laporan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                            <li><a class="dropdown-item" href="{{ route('pemilik.pengeluaran') }}">Pengeluaran</a></li>
                            <li><a class="dropdown-item" href="{{ route('pemilik.keamanan') }}">Keamanan</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-center text-danger" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>