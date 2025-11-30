<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Staf - SIM KOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen p-10">

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Staf</h1>
            <a href="{{ route('staff.menu') }}" class="text-indigo-600 font-medium hover:underline">
                <i class="fas fa-arrow-left"></i> Kembali ke Menu
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <a href="{{ route('staff.lihat_profil', ['id' => 1]) }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all text-center cursor-pointer">
                <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fas fa-user text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Staf 1</h3>
                <p class="text-gray-500 text-sm">Klik untuk melihat detail</p>
            </a>

            <a href="{{ route('staff.lihat_profil', ['id' => 2]) }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all text-center cursor-pointer">
                <div class="w-20 h-20 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                    <i class="fas fa-user-tie text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Staf 2</h3>
                <p class="text-gray-500 text-sm">Klik untuk melihat detail</p>
            </a>

        </div>
    </div>

</body>
</html>
