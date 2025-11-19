<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan Keamanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7; /* Background lebih terang untuk form */
        }
        .header-blue {
            background-color: #1a73e8; /* Biru Google */
        }
        .form-container {
            background-color: #FFFFFF;
            border-radius: 1rem; /* rounded-2xl */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .submit-button {
            background-color: #1a73e8;
            transition: background-color 0.2s;
        }
        .submit-button:hover {
            background-color: #155bb5;
        }
        .cancel-button {
            color: #1a73e8;
            border: 1px solid #1a73e8;
            transition: background-color 0.2s;
        }
        .cancel-button:hover {
            background-color: #e6f1fe;
        }
    </style>
</head>
<body class="min-h-screen">

    {{-- Header --}}
    <header class="header-blue text-white p-4 shadow-md flex items-center justify-between sticky top-0 z-10">
        {{-- Tombol Kembali - Arahkan ke halaman daftar laporan --}}
        <a href="{{ route('staff.laporan_keamanan') }}" class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-xl font-semibold flex-grow text-center pr-10">Tambah Laporan Baru</h1>
        <div class="w-6"></div>
    </header>

    {{-- Main Content - Form --}}
    <main class="container mx-auto p-4 max-w-xl mt-10">
        <form action="{{ route('staff.laporan_keamanan.store') }}" method="POST" class="form-container p-6 sm:p-8">

            {{-- Form Fields --}}
            <div class="space-y-6">

                {{-- 1. Judul Laporan --}}
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul / Subjek Insiden</label>
                    <input type="text" id="judul" name="judul" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                           placeholder="Contoh: Pemadaman Listrik di Lantai 5">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- 2. Tanggal Insiden --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Insiden</label>
                        <input type="date" id="tanggal" name="tanggal" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>

                    {{-- 3. Waktu Insiden --}}
                    <div>
                        <label for="waktu" class="block text-sm font-medium text-gray-700 mb-1">Waktu Insiden</label>
                        <input type="time" id="waktu" name="waktu" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>
                </div>

                {{-- 4. Lokasi Insiden --}}
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kejadian</label>
                    <input type="text" id="lokasi" name="lokasi" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                           placeholder="Contoh: Lantai 5, Depan Kamar 501">
                </div>

                {{-- 5. Deskripsi Detail --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Detail Insiden</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                              placeholder="Jelaskan secara rinci apa yang terjadi, siapa yang terlibat, dan tindakan apa yang telah diambil."></textarea>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('staff.laporan_keamanan') }}"
                   class="cancel-button font-semibold py-2 px-6 rounded-lg transition duration-150 text-base flex items-center justify-center">
                    Batal
                </a>
                <button type="submit"
                        class="submit-button text-white font-semibold py-2 px-6 rounded-lg hover:shadow-lg transition duration-150 text-base flex items-center justify-center">
                    Kirim Laporan
                </button>
            </div>

            {{-- Tambahkan @csrf jika menggunakan Laravel --}}
            {{-- @csrf --}}
        </form>
    </main>

</body>
</html>
