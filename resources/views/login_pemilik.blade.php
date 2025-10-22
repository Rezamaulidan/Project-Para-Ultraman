<html>

<head>
    <link rel="stylesheet" href="{{ asset('css/style_pemilik.css') }}">
    <title>SIMK-Login</title>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="/homepemilik" method="HEAD">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>

</html>