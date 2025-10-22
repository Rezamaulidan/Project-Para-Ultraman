<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya tambahan untuk efek hover yang halus pada item menu */
        .menu-item:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
            background-color: #e5e7eb; /* Warna abu-abu yang sedikit lebih terang saat di-hover */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-blue-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">

            {{-- Logo SIMK (Ganti dengan path asset Anda) --}}
            <div class="flex items-center space-x-3">
                <img src="{{ asset('img/logo-simk.png') }}" alt="Logo SIMK" class="h-8 w-auto object-contain">
                <h1 class="text-xl font-semibold">Menu Staff</h1>
            </div>

            <a href="{{ route('logout') }}"
               class="bg-white text-blue-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-200 transition duration-150 shadow-md">
                Keluar
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8 mt-12">

        <a href="{{ route('staff.manajemen') }}"
           class="menu-item block mb-6 transition duration-300 ease-in-out transform">
            <div class="flex items-center bg-gray-200 rounded-lg p-5 shadow-lg">

                {{-- Area Ikon Manajemen Staff --}}
                <div class="w-16 h-16 mr-6 flex-shrink-0">
                    {{-- Ganti dengan path gambar aset Anda --}}
                    <img src="{{ asset('images/icon_manajemen_staff.png') }}"
                         alt="Ikon Manajemen Staff"
                         class="w-full h-full object-cover">
                </div>

                <p class="text-2xl font-medium text-gray-800">
                    Manajemen Staff
                </p>
            </div>
        </a>

        <a href="{{ route('staff.penyewa') }}"
           class="menu-item block transition duration-300 ease-in-out transform">
            <div class="flex items-center bg-gray-200 rounded-lg p-5 shadow-lg">

                {{-- Area Ikon Informasi Penyewa --}}
                <div class="w-16 h-16 mr-6 flex-shrink-0">
                    {{-- Ganti dengan path gambar aset Anda --}}
                    <img src="{{ asset('images/icon_informasi_penyewa.png') }}"
                         alt="Ikon Informasi Penyewa"
                         class="w-full h-full object-cover">
                </div>

                <p class="text-2xl font-medium text-gray-800">
                    Informasi Penyewa
                </p>
            </div>
        </a>
    </main>

</body>
</html>
