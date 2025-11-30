<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Shift - SIM KOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md overflow-hidden">

        <div class="bg-indigo-600 p-6 text-center relative">
            <a href="{{ route('staff.menu') }}" class="absolute left-4 top-4 text-white/80 hover:text-white">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-white">Absensi Staff</h1>
            <p class="text-indigo-100 text-sm mt-1">{{ date('d F Y') }}</p>
        </div>

        <div class="p-8">

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Gagal!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if(session('sukses'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Berhasil Masuk!</p>
                    <p>{{ session('sukses') }}</p>
                </div>
            @endif

            <form action="{{ route('staff.absen.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Masukkan ID Staff / NIP</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-badge text-gray-400"></i>
                        </div>
                        <input type="number" name="id_staf" placeholder="Contoh: 1" required
                            class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <label class="block text-gray-700 text-sm font-bold mb-3">Pilih Shift Anda Saat Ini:</label>
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <label class="cursor-pointer">
                        <input type="radio" name="shift" value="pagi" class="peer sr-only" required>
                        <div class="rounded-xl border-2 border-gray-200 p-4 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all hover:bg-gray-50">
                            <i class="fas fa-sun text-orange-400 text-2xl mb-2"></i>
                            <div class="text-sm font-semibold text-gray-600">Pagi</div>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="shift" value="siang" class="peer sr-only">
                        <div class="rounded-xl border-2 border-gray-200 p-4 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all hover:bg-gray-50">
                            <i class="fas fa-cloud-sun text-yellow-500 text-2xl mb-2"></i>
                            <div class="text-sm font-semibold text-gray-600">Siang</div>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="shift" value="malam" class="peer sr-only">
                        <div class="rounded-xl border-2 border-gray-200 p-4 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all hover:bg-gray-50">
                            <i class="fas fa-moon text-indigo-800 text-2xl mb-2"></i>
                            <div class="text-sm font-semibold text-gray-600">Malam</div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-indigo-700 shadow-lg transition-transform transform hover:scale-[1.02]">
                    HADIR / PRESENSI
                </button>

            </form>
        </div>
    </div>

</body>
</html>
