<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Staff - SIM KOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen p-6">

<div class="max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manajemen Tim Staff</h1>
            <p class="text-slate-500 mt-1 flex items-center gap-2">
                <i class="fas fa-users text-indigo-500"></i>
                Daftar rekan kerja dan jadwal operasional.
            </p>
        </div>
        <a href="{{ route('staff.menu') }}" class="group bg-white border border-slate-200 hover:border-indigo-300 text-slate-600 hover:text-indigo-600 px-5 py-2.5 rounded-xl shadow-sm transition-all duration-300 flex items-center gap-2 font-medium">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Menu
        </a>
    </div>

    <!-- Grid Card Staff -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($semua_staf as $s)

        <!-- Logic Warna Berdasarkan Shift -->
        @php
            $isPagi = $s->jadwal == 'Pagi';
            $bgGradient = $isPagi ? 'from-orange-400 to-amber-300' : 'from-indigo-600 to-blue-500';
            $shiftIcon = $isPagi ? 'fa-sun' : 'fa-moon';
            $shiftColor = $isPagi ? 'text-orange-600 bg-orange-50 border-orange-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100';
            $avatarColor = $isPagi ? 'text-orange-500' : 'text-indigo-600';
        @endphp

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col h-full">

            <!-- Banner Gradient -->
            <div class="h-24 bg-gradient-to-r {{ $bgGradient }} relative">
                <!-- Badge Shift -->
                <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-md text-white px-3 py-1 rounded-full text-xs font-bold border border-white/30 shadow-sm flex items-center gap-1">
                    <i class="fas {{ $shiftIcon }}"></i> {{ $s->jadwal }}
                </div>
            </div>

            <div class="px-6 pb-6 flex-1 flex flex-col relative">
                <!-- Foto Profil (Inisial) -->
                <div class="w-20 h-20 bg-white p-1.5 rounded-full absolute -top-10 left-1/2 transform -translate-x-1/2 shadow-lg">
                    <div class="w-full h-full bg-slate-50 rounded-full flex items-center justify-center text-2xl font-bold {{ $avatarColor }} uppercase tracking-widest border border-slate-100">
                        {{ substr($s->nama_staf, 0, 2) }}
                    </div>
                </div>

                <!-- Info Staff -->
                <div class="mt-12 text-center flex-1">
                    <h3 class="text-lg font-bold text-slate-800 leading-tight">{{ $s->nama_staf }}</h3>
                    <p class="text-xs text-slate-400 font-mono mt-1 mb-4">ID: #{{ str_pad($s->id_staf, 4, '0', STR_PAD_LEFT) }}</p>

                    <!-- Detail Kontak -->
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center gap-3 text-sm text-slate-600 bg-slate-50 p-2 rounded-lg">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-indigo-500">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span class="truncate text-xs font-medium">{{ $s->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi (Footer Card) -->
                <div class="mt-auto pt-4 border-t border-slate-100 grid grid-cols-2 gap-3">
                    <a href="https://wa.me/{{ $s->no_hp }}" target="_blank" class="flex items-center justify-center gap-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white py-2 rounded-lg text-sm font-semibold transition-colors duration-300">
                        <i class="fab fa-whatsapp text-lg"></i> Chat
                    </a>

                    <button class="flex items-center justify-center gap-2 bg-slate-50 text-slate-500 hover:bg-slate-200 hover:text-slate-700 py-2 rounded-lg text-sm font-semibold transition-colors duration-300 cursor-default" title="Status Aktif">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Aktif
                    </button>
                </div>
            </div>
        </div>
        @empty
        <!-- State Kosong -->
        <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-user-slash text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-600">Belum ada data staf lain.</h3>
            <p class="text-slate-400 text-sm">Data staf akan muncul di sini setelah ditambahkan.</p>
        </div>
        @endforelse
    </div>

</div>

</body>
</html>
