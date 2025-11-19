<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keamanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2D3748; /* Darker gray background */
        }
        .header-blue {
            background-color: #1a73e8; /* Biru Google */
        }
        .report-card {
            background-color: #FFFFFF;
            border-radius: 0.75rem; /* rounded-xl */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.08);
        }
        .add-button {
            background-color: #1a73e8; /* Biru Google */
            border-radius: 9999px; /* rounded-full */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
        }
        .add-button:hover {
            background-color: #155bb5; /* Biru sedikit lebih gelap */
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-gray-800 min-h-screen">

    {{-- Header --}}
    <header class="header-blue text-white p-4 shadow-md flex items-center justify-between sticky top-0 z-10">
        <a href="#" class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{-- <span class="text-lg">Kembali</span> --}}
        </a>
        <h1 class="text-xl font-semibold flex-grow text-center pr-10">Laporan Keamanan</h1>
        <div class="w-6"></div> {{-- Placeholder untuk menyeimbangkan layout --}}
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto p-4 max-w-md">

        <div class="space-y-4 mt-8">
            {{-- Contoh Laporan Card --}}
            <div class="report-card p-4 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-lg font-semibold text-gray-800">Orang mencurigakan</h2>
                    <span class="text-sm text-gray-500 flex-shrink-0 ml-4">19/05/2025 | 19:47</span>
                </div>
                <p class="text-sm text-gray-600 leading-snug">Ditemukan tamu laki-laki yang masuk tanpa izin ke kama...</p>
            </div>

            <div class="report-card p-4 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-lg font-semibold text-gray-800">Pemadaman listrik</h2>
                    <span class="text-sm text-gray-500 flex-shrink-0 ml-4">11/05/2025 | 02:10</span>
                </div>
                <p class="text-sm text-gray-600 leading-snug">Terjadi pemadaman listrik mendadak sekitar pukul 02.10...</p>
            </div>

            <div class="report-card p-4 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-lg font-semibold text-gray-800">Kehilangan barang</h2>
                    <span class="text-sm text-gray-500 flex-shrink-0 ml-4">10/05/2025 | 07:00</span>
                </div>
                <p class="text-sm text-gray-600 leading-snug">Penghuni kamar 13 melaporkan kehilangan sandal di de...</p>
            </div>

            <div class="report-card p-4 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-lg font-semibold text-gray-800">CCTV mati</h2>
                    <span class="text-sm text-gray-500 flex-shrink-0 ml-4">5/05/2025 | 09:05</span>
                </div>
                <p class="text-sm text-gray-600 leading-snug">Salah satu CCTV di lorong lantai 2 mati sejak pukul 09.00...</p>
            </div>

            <div class="report-card p-4 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-lg font-semibold text-gray-800">Kehilangan barang</h2>
                    <span class="text-sm text-gray-500 flex-shrink-0 ml-4">1/05/2025 | 07:00</span>
                </div>
                <p class="text-sm text-gray-600 leading-snug">Penghuni kamar 7 melaporkan kehilangan helm yang di...</p>
            </div>

            {{-- Anda bisa menambahkan lebih banyak laporan di sini --}}

        </div>

        {{-- Tombol Tambah Laporan --}}
        <div class="mt-10 text-center">
            <button class="add-button text-white font-semibold py-3 px-8 text-lg hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Tambah Laporan
            </button>
        </div>

    </main>

</body>
</html>
