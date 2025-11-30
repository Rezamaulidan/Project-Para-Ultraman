<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penyewa - Staff View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen p-8">

    <div class="max-w-6xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Informasi Penyewa</h1>
                <p class="text-gray-500 text-sm mt-1">Daftar semua penyewa yang terdaftar di database.</p>
            </div>
            <a href="{{ route('staff.menu') }}" class="text-indigo-600 font-medium hover:text-indigo-800">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Menu
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Penyewa</th>
                            <th class="px-6 py-4">Kontak (HP/WA)</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Alamat Asal</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftar_penyewa as $index => $penyewa)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500 text-sm">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        {{ strtoupper(substr($penyewa->nama_lengkap ?? $penyewa->username, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $penyewa->nama_lengkap ?? $penyewa->username }}</p>
                                        <p class="text-xs text-gray-400">ID: {{ $penyewa->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($penyewa->no_hp)
                                    <a href="https://wa.me/{{ $penyewa->no_hp }}" target="_blank" class="text-green-600 hover:underline">
                                        <i class="fab fa-whatsapp me-1"></i> {{ $penyewa->no_hp }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $penyewa->email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $penyewa->alamat ?? 'Belum diisi' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Aktif</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data penyewa di database.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 text-xs text-gray-500">
                Total Penyewa: <strong>{{ $daftar_penyewa->count() }}</strong> Orang
            </div>
        </div>
    </div>

</body>
</html>
