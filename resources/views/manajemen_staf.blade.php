<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Profil Staf</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen py-10">

    <div class="max-w-3xl mx-auto px-4">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detail Profil Staf</h2>

            <a href="{{ route('staff.menu') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        @if(session('sukses'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Berhasil!</strong> {{ session('sukses') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="h-24 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

            <div class="px-8 pb-8 text-center relative">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}&background=fff&color=4f46e5&size=128"
                     class="w-24 h-24 rounded-full border-4 border-white mx-auto -mt-12 shadow-md object-cover bg-white">

                <h3 class="text-xl font-bold text-gray-800 mt-3">{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</h3>
                <p class="text-indigo-600 font-medium text-sm">Staf Operasional</p>

                <div class="mt-6 border-t border-gray-100 pt-6">
                    <a href="{{ route('staff.manajemen.edit') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition-all shadow-sm">
                        <i class="fas fa-pen"></i> Edit Staf Ini
                    </a>
                </div>
            </div>

            <div class="bg-gray-50 p-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                    <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Username</span>
                    <p class="text-gray-800 font-semibold mt-1">{{ Auth::user()->username }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                    <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Email</span>
                    <p class="text-gray-800 font-semibold mt-1">{{ Auth::user()->email ?? '-' }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                    <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">No WhatsApp</span>
                    <p class="text-gray-800 font-semibold mt-1">{{ Auth::user()->no_hp ?? '-' }}</p>
                </div>
                <div class="md:col-span-2 bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                    <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Alamat Domisili</span>
                    <p class="text-gray-800 font-semibold mt-1">{{ Auth::user()->alamat ?? 'Belum diisi' }}</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
