<div class="header-container">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-simk.png') }}" alt="Logo SIMK" class="me-3">
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
                        <a class="nav-link {{ Request::is('homepemilik') ? 'active' : '' }} text-center"
                            aria-current="page" href="/homepemilik">
                            <i class="fa-solid fa-house me-1"></i> Beranda
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-center {{ Request::is('datakamarpemilik') || Request::is('datapenyewapemilik') || Request::is('datastaffpemilik') ? 'active' : '' }}"
                            href="/datakamarpemilik" id="infoDataDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-circle-info me-1"></i> Informasi Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="infoDataDropdown">
                            <li><a class="dropdown-item" href="/datakamarpemilik">Kamar</a></li>
                            <li><a class="dropdown-item" href="/datapenyewapemilik">Penyewa</a></li>
                            <li><a class="dropdown-item" href="/datastaffpemilik">Staff</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('transaksipemilik') ? 'active' : '' }} text-center"
                            aria-current="page" href="/transaksipemilik">
                            <i class="fa-solid fa-receipt me-1"></i> Transaksi
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-center {{ Request::is('pengeluaranpemilik') || Request::is('keamananpemilik') ? 'active' : '' }}"
                            href="/laporanpemilik" id="infoDataDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-chart-line me-1"></i> Laporan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                            <li><a class="dropdown-item" href="/pengeluaranpemilik">Pengeluaran</a></li>
                            <li><a class="dropdown-item" href="/keamananpemilik">Keamanan</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-center {{ Request::is('registrasistaff') ? 'active' : '' }}"
                            href="/laporanpemilik" id="infoDataDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-sitemap me-1"></i> Lainnya
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="lainnyaDropdown">
                            <li><a class="dropdown-item" href="/registrasistaff">Registrasi staff</a></li>
                            <li><a class="dropdown-item" href="/login"
                                    onclick="event.preventDefault(); if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) { document.getElementById('logout-form').submit(); }">Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<form id="logout-form" action="/login" method="HEAD" style="display: none;">
</form>