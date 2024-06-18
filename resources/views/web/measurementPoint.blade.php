<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | Main</title>
    <!-- Include Tabulator CSS from CDN -->
    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet" />
    <!-- Include Tabulator JS from CDN -->
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>
    @vite(["resources/scss/measurement_point.scss","resources/js/measurement_point.js","resources/scss/base.scss"])
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="{{ route('home') }}">
                <img id="geoscan_logo" src="{{ asset('image/geoscan-logo.png') }}" alt="geoscan-logo"></img>
            </a>
        </div>

        <form class='button-form' action="{{ route('logout') }}" method="POST" role="search">
            @csrf
            @method('DELETE')
            <button class="logout-button">Logout</button>
        </form>
    </nav>
    <div class="container">
        <h1> Measurement Points</h1>
        <h3>Project Information</h3>
        <table>
            <tr>
                <th>PJO Number</th>
                <td>{{$project['job_number']}}</td>
            </tr>
            <tr>
                <th>Client</th>
                <td>{{$project['client_name']}}</td>

            </tr>
            <tr>
                <th>Location</th>
                <td>{{$project['jobsite_location']}}</td>
            </tr>
            <tr>
                <th>Project Description</th>
                <td>{{$project['project_description']}}</td>
            </tr>
            <tr>
                <th>BCA Reference Number</th>
                <td>{{$project['bca_reference_number']}}</td>
            </tr>
            <tr>
                <th>SMS Alerts</th>
                <td>{{$project['sms_count']}}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{$project['status']}}</td>
            </tr>
            <th>Contacts</th>
        </table>
        <div id="contacts_table"></div>
        <div id="measurement_point_table"></div>

    </div>

    <div class=" footer">
        <p>Tel: +65 6781 1919</p>
        <p>Fax: +65 6781 9297</p>
        <p>Email: enquiry@geoscan.com.sg</p>
        <p>Web: geoscan.com.sg</p>
    </div>

</body>

</html>
