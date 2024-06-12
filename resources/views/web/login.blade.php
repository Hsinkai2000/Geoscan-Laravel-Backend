<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    @vite(["resources/scss/login.scss"]);
</head>

<body>
    <div class="container">
        <img id="geoscan_logo" src="{{ asset('image/geoscan-logo.png') }}" alt="geoscan-logo" />

        <h2>Realtime Geoscan</h2>

        <h3>User Login</h3>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="login-container">
                <p>Username:</p>
                <input id="username" class="input username" name="username" />
            </div>
            <div class="login-container">
                <p>Password:</p>
                <input type='password' id="password" class="input username" name="password" />
            </div>
            <p class="error-message"></p>
            <button id="btn-login" class="btn-login">Login</button>
        </form>
    </div>
    <div class="footer">
        <p>Tel: +65 6781 1919</p>
        <p>Fax: +65 6781 9297</p>
        <p>Email: enquiry@geoscan.com.sg</p>
        <p>Web: geoscan.com.sg</p>
    </div>
</body>

</html>
