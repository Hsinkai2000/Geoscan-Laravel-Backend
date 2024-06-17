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
    @vite(["resources/scss/home.scss","resources/js/home.js","resources/scss/base.scss"])
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
        <h1> Welcome, {{ Auth::user()->username }}</h1>
        <!-- <button id="ajax-trigger">Load Data via AJAX</button>
        <div id="example-table"></div> -->
        <div class="button-container">
            <a href="{{ route('project.show') }}" class="btn">Project</a>
            <a href="{{ route('measurement_point.show') }}" class="btn">Measurement Point</a>
            <a href="{{ route('project.show') }}" class="btn">Concentrator</a>
            <a href="{{ route('project.show') }}" class="btn">Noise Meter</a>
            <a href="{{ route('project.show') }}" class="btn">Contact</a>
            <a href="{{ route('project.show') }}" class="btn">Contact</a>
        </div>

        <div class="footer">
            <p>Tel: +65 6781 1919</p>
            <p>Fax: +65 6781 9297</p>
            <p>Email: enquiry@geoscan.com.sg</p>
            <p>Web: geoscan.com.sg</p>
        </div>
        <!--
        <script>
        // Example data

        async function getData() {
            console.log('btn pressed');
            var tabledata = null;
            try {

                fetch("http://localhost:8000/project", {
                    method: "get",
                    headers: {
                        "Content-type": "application/json; charset=UTF-8",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                }).then((response) => {
                    if (!response.ok) {
                        throw new Error("User not Authorised");
                    }
                    return response.json();
                }).then((json) => {
                    console.log(json);
                    tabledata = json.projects;
                    table.setData(tabledata);
                });
            } catch (error) {
                return error;
            }
        }


        //Build Tabulator
        var table = new Tabulator("#example-table", {
            height: "311px",
            layout: "fitColumns",
            placeholder: "No Data Set",
            autoColumns: true
        });

        //trigger AJAX load on "Load Data via AJAX" button click
        document
            .getElementById("ajax-trigger")
            .addEventListener("click", function() {
                tabledata = getData();
            });
        </script> -->


</body>

</html>
