<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - SIM KOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="max-w-3xl mx-auto mt-10 p-6">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Edit Profil Staf</h2>

            <a href="{{ route('staff.lihat_profil') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm transition-colors">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        @if(session('sukses'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('sukses') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="h-32 bg-indigo-600 w-full relative"></div>
            <div class="px-8 pb-8">
                <div class="relative -mt-16 mb-6 text-center">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}&background=fff&color=4f46e5&size=128"
                         class="w-32 h-32 rounded-full border-4 border-white mx-auto shadow-md object-cover">
                </div>

                <form action="{{ route('staff.manajemen.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" value="{{ Auth::user()->username }}" readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                            <input type="text" value="Staf Operasional" readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-gray-500 cursor-not-allowed">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" placeholder="Masukkan nama lengkap" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ Auth::user()->no_hp }}" placeholder="0812..." required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ Auth::user()->email }}" placeholder="email@contoh.com" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Domisili</label>
                            <textarea name="alamat" rows="3" placeholder="Alamat lengkap..." required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">{{ Auth::user()->alamat }}</textarea>
                        </div>

                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('staff.lihat_profil') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all">
                            Batal
                        </a>

                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-sm transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>
