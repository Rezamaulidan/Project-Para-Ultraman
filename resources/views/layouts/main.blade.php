<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMK - @yield('title', 'Sistem Informasi Manajemen Kos')</title>

    {{-- Link CDN Bootstrap 5 (Karena kode Anda menggunakan class Bootstrap) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    {{--
        Anda bisa menambahkan Navbar/Header di sini jika menggunakan partials.
        Contoh: @include('partials.navbar')
    --}}

    <main>
        {{-- AREA PENTING: Konten dari home.blade.php akan dimasukkan di sini --}}
        @yield('container')
    </main>

    {{-- Script JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
