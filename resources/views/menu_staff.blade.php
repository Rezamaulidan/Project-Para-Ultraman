<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMK - Menu Staff</title>
    {{-- Memuat Tailwind CSS melalui CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Mengatur warna biru kustom yang mendekati tema ASTON */
        .aston-blue {
            background-color: #008080; /* Teal/Biru Kehijauan Kustom */
        }

        /* Gaya tambahan untuk efek hover yang halus pada item menu */
        .menu-item {
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            /* Memastikan semua kotak menu tingginya sama */
            height: 100%;
        }
        .menu-item:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px) scale(1.02);
            background-color: #f0fdf4; /* Warna hijau mint muda untuk hover */
        }

        /* Gaya untuk Hero Section (Gambar Latar Belakang) */
        .hero-section {
            /* Pastikan ganti dengan path aset gambar Anda! */
            /* Untuk contoh ini, saya menggunakan warna solid abu-abu dari screenshot Anda */
            background-color: #A0A0A0;
            /* Jika ingin gambar seperti ASTON, ganti dengan: background-image: url('{{ asset('images/aston_hero_background.jpg') }}'); */
            background-size: cover;
            background-position: center;
            height: 400px; /* Ketinggian hero section */
            position: relative;
        }

        /* Overlay untuk membuat teks lebih mudah dibaca (disesuaikan agar transparan) */
        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.1); /* Overlay hitam sangat tipis/transparan */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- HEADER MIRIP DENGAN SIMK DI SCRENSHOT ANDA --}}
    <header class="aston-blue text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">

            {{-- Logo dan Judul Sistem --}}
            <div class="flex items-center space-x-4">
                {{-- Logo SIMK (Ganti dengan path asset Anda) --}}
                <img src="{{ asset('img/logo-simk.png') }}" alt="Logo SIMK" class="h-8 w-auto object-contain filter brightness-125">
                <h1 class="text-xl sm:text-2xl font-bold tracking-wider">SIMK</h1>
                <span class="hidden sm:inline-block text-lg border-l border-white/50 pl-4 ml-4">Sistem Informasi Manajemen Kepegawaian</span>
            </div>

            {{-- Tombol Keluar --}}
            <a href="{{ route('logout') }}"
               class="bg-white text-gray-800 font-semibold py-2 px-5 rounded-full hover:bg-gray-200 transition duration-150 shadow-md">
                Keluar
            </a>
        </div>
    </header>

    {{-- HERO SECTION (SEPERTI FOTO LATAR ASTON, diubah menjadi warna abu-abu solid seperti screenshot kedua Anda) --}}
    <div class="hero-section">
        <div class="hero-overlay absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white p-8">
                <p class="text-xl font-light mb-4 tracking-widest uppercase text-gray-700">Selamat Datang,</p>
                <h2 class="text-4xl sm:text-6xl font-extrabold leading-tight text-gray-900">
                    MENU STAFF
                </h2>
                <p class="text-lg mt-4 opacity-90 text-gray-700">SIMK - Kemudahan Akses Informasi Anda</p>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT - MENU STAFF --}}
    <main class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 -mt-20 relative z-10">

        <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">Pilih Modul yang Ingin Diakses</h3>

        {{-- Grid 4 Kolom untuk Menu --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

            {{-- 1. Modul Manajemen Staff --}}
            <a href="{{ route('staff.manajemen') }}"
               class="menu-item flex flex-col justify-center items-center bg-white rounded-xl shadow-lg hover:shadow-2xl text-center p-6">
                {{-- Area Ikon --}}
                <div class="w-12 h-12 mb-3 flex-shrink-0 bg-blue-100 rounded-full p-2 flex items-center justify-center">
                                         <img src="{{ asset('images/icon_manajemen_staff.png') }}"
                         alt="Ikon Manajemen Staff"
                         class="w-full h-full object-contain">
                </div>
                <p class="text-base font-semibold text-gray-800">
                    Manajemen Staff
                </p>
            </a>

            {{-- 2. Modul Informasi Penyewa --}}
            <a href="{{ route('staff.penyewa') }}"
               class="menu-item flex flex-col justify-center items-center bg-white rounded-xl shadow-lg hover:shadow-2xl text-center p-6">
                {{-- Area Ikon --}}
                <div class="w-12 h-12 mb-3 flex-shrink-0 bg-green-100 rounded-full p-2 flex items-center justify-center">
                                        <img src="{{ asset('images/icon_informasi_penyewa.png') }}"
                         alt="Ikon Informasi Penyewa"
                         class="w-full h-full object-contain">
                </div>
                <p class="text-base font-semibold text-gray-800">
                    Informasi Penyewa
                </p>
            </a>

            {{-- 3. Modul Laporan Keamanan (Baru Ditambahkan) --}}
            <a href="{{ route('staff.laporan_keamanan') }}"
               class="menu-item flex flex-col justify-center items-center bg-white rounded-xl shadow-lg hover:shadow-2xl text-center p-6">
                {{-- Area Ikon --}}
                <div class="w-12 h-12 mb-3 flex-shrink-0 bg-red-100 rounded-full p-2 flex items-center justify-center">
                    {{-- Ikon (Ganti dengan aset Anda) --}}
                                    </div>
                <p class="text-base font-semibold text-gray-800">
                    Laporan Keamanan
                </p>
            </a>

            {{-- 4. Modul Shift Kerja (Baru Ditambahkan) --}}
            <a href="{{ route('staff.shift_kerja') }}"
               class="menu-item flex flex-col justify-center items-center bg-white rounded-xl shadow-lg hover:shadow-2xl text-center p-6">
                {{-- Area Ikon --}}
                <div class="w-12 h-12 mb-3 flex-shrink-0 bg-yellow-100 rounded-full p-2 flex items-center justify-center">
                    {{-- Ikon (Ganti dengan aset Anda) --}}
                                     </div>
                <p class="text-base font-semibold text-gray-800">
                    Shift Kerja (Jadwal)
                </p>
            </a>
        </div>
    </main>

    <footer class="mt-20 py-4 text-center text-gray-500 text-sm border-t border-gray-200">
        &copy; {{ date('Y') }} SIMK - Sistem Informasi Manajemen Kepegawaian
    </footer>

</body>
</html>
