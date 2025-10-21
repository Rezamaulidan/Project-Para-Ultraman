<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* CSS Tambahan untuk penyesuaian ikon pada input (dari kode Anda) */
        .input-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280; /* Warna abu-abu ikon */
        }
        /* Tambahan untuk menyembunyikan ikon mata bawaan dari kode sebelumnya jika tidak diimplementasikan */
        .input-group .password-toggle-icon {
            display: none; /* Sembunyikan jika tidak ada JS untuk mengaktifkannya */
        }
    </style>
</head>
<body class="bg-blue-500 flex justify-center items-center min-h-screen">

    <div class="w-full max-w-sm p-8 bg-white rounded-2xl shadow-2xl">

        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/illustration_login.png') }}" alt="Ilustrasi Login" class="w-48 h-auto object-contain">
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mb-8">
            Masuk
        </h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="username" class="text-sm text-gray-700 block mb-1">Username</label>
                <input type="text" id="username" name="username" placeholder="" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-base">
            </div>

            <div class="mb-2">
                <label for="password" class="text-sm text-gray-700 block mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-base">
            </div>

            <div class="text-right mb-6 text-sm">
                <a href="{{ route('password.request') }}" class="text-blue-500 hover:text-blue-700">Lupa kata sandi?</a>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition duration-200 text-lg">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 mb-4">
                Belum punya akun? jelajahi sebagai tamu
            </p>

            <a href="#"
               class="w-full inline-block py-3 font-semibold rounded-lg border-2 border-blue-500 text-blue-500 hover:bg-blue-50 transition duration-150 text-lg">
                Masuk Sebagai Tamu
            </a>
            </div>
    </div>

</body>
</html>
