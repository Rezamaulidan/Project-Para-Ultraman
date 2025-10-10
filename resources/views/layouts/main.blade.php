<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    {{-- FONTT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/footers/">

    {{-- FONT FAMILY --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800&family=Roboto:wght@100;300;400;500;700;900&family=Zen+Dots&display=swap"
        rel="stylesheet">

    {{-- ICON BOOTSTRAP --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- ICON LOGO PADA HEADER --}}
    {{-- <link rel="icon" href="/img/logo-ikbksy.svg" type="image/x-icons"> --}}
    <link rel="icon" href="{{ asset('img/logo-ikbksy.svg') }}" type="image/x-icons">

    {{-- CSS --}}
    <link rel="stylesheet" href="/css/home.css">
</head>

<body>

    <div class="d-flex flex-column min-vh-100">
        <header>
            @include('partials.navbar')
        </header>

        <main class="flex-grow-1">
            @yield('container')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
