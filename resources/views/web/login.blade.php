<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    @vite(["resources/scss/login.scss","resources/js/login.js"]);
</head>

<body>
    <div class="container">
        <img id="geoscan_logo" src="{{ asset('image/geoscan-logo.png') }}" alt="geoscan-logo" />

        <h2>Realtime Geoscan</h2>

        <h3>User Login</h3>

        <div class="login-container">
            <p>Username:</p>
            <input id="input_username" class="input username" />
        </div>
        <div class="login-container">
            <p>Password:</p>
            <input id="input_password" class="input username" />
        </div>
        <p class="error-message"></p>
        <button id="btn-login" class="btn-login">Login</button>
    </div>
    <div class="footer">
        <p>Tel: +65 6781 1919</p>
        <p>Fax: +65 6781 9297</p>
        <p>Email: enquiry@geoscan.com.sg</p>
        <p>Web: geoscan.com.sg</p>
    </div>
    <script src="../../js/login.js"></script>
</body>

</html>
