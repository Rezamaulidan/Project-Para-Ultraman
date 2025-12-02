<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Staff - SIM KOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-lg mx-auto bg-white shadow-xl rounded-xl overflow-hidden relative">

    <div class="bg-indigo-600 p-6 relative">
        <a href="{{ route('staff.menu') }}" class="absolute left-4 top-6 text-white hover:text-indigo-200 flex items-center gap-2 transition">
            <i class="fas fa-arrow-left"></i>
            <span class="font-semibold text-sm">Menu</span>
        </a>

        <div class="text-center text-white">
            <h1 class="text-2xl font-bold mt-2">Absensi Staff</h1>
            <p class="text-sm text-indigo-200 mt-1">{{ date('d F Y') }}</p>
        </div>
    </div>

    <div class="p-6">

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-600 text-red-700 p-4 mb-4 rounded shadow-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if(session('sukses'))
            <div class="bg-green-50 border-l-4 border-green-600 text-green-700 p-4 mb-4 rounded shadow-sm">
                <i class="fas fa-check-circle mr-2"></i>{{ session('sukses') }}
            </div>
        @endif

        <form action="{{ route('staff.absen.store') }}" method="POST">
            @csrf

            <label class="block font-semibold text-gray-700 mb-2">Masukkan ID Staff:</label>
            <div class="relative mb-5">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-id-card text-gray-400"></i>
                </div>
                <input type="number" name="id_staf" required placeholder="Contoh: 1"
                       class="w-full pl-10 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">Pilih Shift Kerja:</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500 transition">
                        <input type="radio" name="shift" value="Pagi" class="sr-only peer" required checked>
                        <div class="flex items-center gap-2 text-gray-600 peer-checked:text-indigo-600">
                            <i class="fas fa-sun text-yellow-500"></i>
                            <span class="font-bold">Pagi</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-indigo-600 rounded-lg pointer-events-none"></div>
                    </label>

                    <label class="relative flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500 transition">
                        <input type="radio" name="shift" value="Malam" class="sr-only peer">
                        <div class="flex items-center gap-2 text-gray-600 peer-checked:text-indigo-600">
                            <i class="fas fa-moon text-blue-500"></i>
                            <span class="font-bold">Malam</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-indigo-600 rounded-lg pointer-events-none"></div>
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <button name="aksi" value="masuk" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-in-alt mr-2"></i> MASUK
                </button>

                <button name="aksi" value="pulang" class="flex-1 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-out-alt mr-2"></i> PULANG
                </button>
            </div>
        </form>

        <div class="mt-8">
            <h2 class="text-lg font-bold text-gray-700 border-b pb-2 mb-3 flex items-center">
                <i class="fas fa-list-alt mr-2 text-indigo-500"></i> Daftar Hadir Hari Ini
            </h2>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3 text-center">Shift</th>
                            <th class="px-4 py-3 text-center">Masuk</th>
                            <th class="px-4 py-3 text-center">Pulang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($daftar_hadir as $a)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $a->nama_staf }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $a->shift == 'Pagi' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $a->shift }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-green-600 font-bold">{{ $a->jam_masuk }}</td>
                            <td class="px-4 py-3 text-center text-red-600 font-bold">{{ $a->jam_pulang ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center p-6 text-gray-400 italic">
                                Belum ada staf yang absen hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</body>
</html>
