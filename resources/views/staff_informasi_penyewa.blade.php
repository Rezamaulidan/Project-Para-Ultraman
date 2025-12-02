<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Penyewa - Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-6xl mx-auto">

    <!-- Header -->
    <div class="bg-indigo-600 rounded-t-xl p-6 flex justify-between items-center text-white">
        <div>
            <h1 class="text-2xl font-bold">Data Penyewa Kos</h1>
            <p class="text-indigo-200 text-sm">Daftar semua penyewa yang aktif saat ini.</p>
        </div>
        <!-- Tombol Kembali -->
        <a href="{{ route('staff.menu') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Menu
        </a>
    </div>

    <!-- Tabel Penyewa -->
    <div class="bg-white shadow-xl rounded-b-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-sm font-semibold border-b">
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Penyewa</th>
                        <th class="p-4">No. HP</th>
                        <th class="p-4">Email</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse($penyewa as $index => $p)
                    <tr class="border-b hover:bg-indigo-50 transition">
                        <td class="p-4 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td class="p-4 font-bold text-gray-800">
                            {{ $p->nama_penyewa ?? 'Tanpa Nama' }}
                        </td>
                        <td class="p-4">
                            <a href="https://wa.me/{{ $p->no_hp ?? '' }}" target="_blank" class="text-green-600 hover:text-green-800 font-semibold flex items-center gap-2">
                                <i class="fab fa-whatsapp"></i> {{ $p->no_hp ?? '-' }}
                            </a>
                        </td>
                        <td class="p-4 text-gray-500">{{ $p->email ?? '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                Aktif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400 italic">
                            <i class="fas fa-users-slash text-4xl mb-2 block"></i>
                            Belum ada data penyewa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-gray-50 text-xs text-gray-500 text-center border-t">
            Menampilkan {{ count($penyewa) }} data penyewa.
        </div>
    </div>

</div>

</body>
</html>
