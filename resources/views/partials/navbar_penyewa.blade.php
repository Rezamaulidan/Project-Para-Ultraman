@php
    // Cek apakah user yang login memiliki setidaknya satu booking yang LUNAS
    // Kita taruh logika ini di sini agar berlaku di semua halaman yang memanggil navbar ini
    $isPenghuniSah = \App\Models\Booking::where('username', Auth::user()->username)
        ->where('status_booking', 'lunas')
        ->exists();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark sticky-top"
    style="background: linear-gradient(135deg, #001931, #003366); box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <div class="container">
        {{-- LOGO BRAND --}}
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('penyewa.dashboard') }}">
            <i class="fas fa-home me-2 text-warning"></i> SIMK <span class="fw-light ms-1">Penyewa</span>
        </a>

        {{-- TOGGLE BUTTON (MOBILE) --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU ITEMS --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                {{-- 1. DASHBOARD (Bisa Diakses Semua Status) --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('penyewa.dashboard') ? 'active fw-bold' : '' }}"
                        href="{{ route('penyewa.dashboard') }}">
                        <i class="fas fa-columns me-1"></i> Dashboard
                    </a>
                </li>

                {{-- 2. CARI KAMAR (Hanya Jika BELUM Jadi Penghuni Sah) --}}
                @if (!$isPenghuniSah)
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('dashboard.booking') ? 'active fw-bold' : '' }}"
                            href="{{ route('dashboard.booking') }}">
                            <i class="fas fa-search me-1"></i> Cari Kamar
                        </a>
                    </li>
                @endif

                {{-- 3. MENU EKSKLUSIF (Hanya Jika SUDAH LUNAS) --}}
                @if ($isPenghuniSah)
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('penyewa.pembayaran') ? 'active fw-bold' : '' }}"
                            href="{{ route('penyewa.pembayaran') }}">
                            <i class="fas fa-history me-1"></i> Riwayat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('penyewa.keamanan') ? 'active fw-bold' : '' }}"
                            href="{{ route('penyewa.keamanan') }}">
                            <i class="fas fa-shield-alt me-1"></i> Keamanan
                        </a>
                    </li>
                @endif

                {{-- 4. DROPDOWN PROFIL (Kanan) --}}
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center bg-white bg-opacity-10 rounded-pill px-3 py-1"
                        href="#" role="button" data-bs-toggle="dropdown">

                        {{-- Foto Profil Kecil --}}
                        <img src="{{ Auth::user()->penyewa->foto_profil ? asset('storage/' . Auth::user()->penyewa->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                            class="rounded-circle me-2" style="width: 25px; height: 25px; object-fit: cover;">

                        <span
                            class="small text-white">{{ Str::limit(Auth::user()->penyewa->nama_penyewa ?? Auth::user()->username, 10) }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2">
                        <li>
                            <div class="dropdown-header text-muted small fw-bold">AKUN SAYA</div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('penyewa.informasi') }}">
                                <i class="fas fa-user-circle me-2 text-primary"></i> Edit Profil
                            </a>
                        </li>

                        {{-- Menu Kamar Saya hanya muncul jika Lunas --}}
                        @if ($isPenghuniSah)
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('penyewa.kamar') }}">
                                    <i class="fas fa-bed me-2 text-success"></i> Kamar Saya
                                </a>
                            </li>
                        @endif

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger fw-bold">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
