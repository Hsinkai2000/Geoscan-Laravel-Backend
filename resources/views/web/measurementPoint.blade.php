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
    @vite(['resources/scss/measurement_point.scss', 'resources/js/app.js', 'resources/js/measurement_point.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img class="me-2" id="geoscan_logo" style="width: 50px;"
                    src="{{ asset('image/geoscanlogo_yellow.png') }}" alt="geoscan-logo"></img>Geoscan NMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Concentrators</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Noise Meters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Users</a>
                    </li>
                </ul>
                <form class="d-flex" action="{{ route('logout') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-secondary" type="submit">logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid pt-3 p-5">
        <h3 class="text-dark">Measurement Points</h3>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('project.show') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="#">Measurement Point</a></li>
            </ol>
        </nav>
        <div class="mb-3">
            <h5 class="d-inline me-4">Project Information</h5>
            <button class="d-inline btn btn-primary bg-light text-primary shadow-sm"
                onclick="fetch_users('inputUpdateUserSelect', {{ $project['user_id'] }})" data-bs-toggle="modal"
                data-bs-target="#updateModal">Edit Project</button>
        </div>
        <table class=" table">
            <tr>
                <th scope='row'>PJO Number</th>
                <td scope='row'>{{ $project['job_number'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Client</th>
                <td scope='row'>{{ $project['client_name'] }}</td>

            </tr>
            <tr>
                <th scope='row'>Location</th>
                <td scope='row'>{{ $project['jobsite_location'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Project Description</th>
                <td scope='row'>{{ $project['project_description'] }}</td>
            </tr>
            <tr>
                <th scope='row'>BCA Reference Number</th>
                <td scope='row'>{{ $project['bca_reference_number'] }}</td>
            </tr>
            <tr>
                <th scope='row'>SMS Alerts</th>
                <td scope='row'>{{ $project['sms_count'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Status</th>
                <td scope='row'>{{ $project['status'] }}</td>
            </tr>
        </table>
        <div class="bg-light p-2 mb-3 shadow rounded">
            <h5>Contacts</h5>
            <div class="shadow" id="contacts_table"></div>
        </div>
        <div class="rounded bg-light p-2 shadow ">

            <h5>Measurement Points Information</h5>

            <div id="measurement_point_table"></div>


            <div class="d-flex flex-row mt-3 justify-content-between">
                <button class="btn btn-light text-danger border shadow-sm" id="deleteButton" data-bs-toggle="modal"
                    data-bs-target="#deleteModal">Delete</button>

                <div id="measurement_point_pages"></div>

                <div>
                    <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm" id="editButton"
                        onclick="" data-bs-toggle="modal" data-bs-target="#updateModal">Edit</button>
                    <button class="btn btn-primary text-light  shadow-sm" id="createButton"
                        onclick='fetch_users("inputUserSelect")' data-bs-toggle="modal"
                        data-bs-target="#projectcreateModal">Create</button>
                </div>
            </div>
        </div>

        <x-project-update-modal :project="$project" />
        <x-delete-modal type='measurement point' />

        <input hidden name="id" id="inputprojectId" value="{{ $project['id'] }}">

    </div>
</body>

</html>
