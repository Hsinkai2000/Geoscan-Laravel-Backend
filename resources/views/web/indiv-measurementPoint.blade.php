<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | {{ $measurementPoint->point_name }}</title>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Tabulator CSS from CDN -->
    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet" />
    <!-- Include Tabulator JS from CDN -->
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/scss/indiv_measurement_point.scss', 'resources/js/app.js', 'resources/js/indiv.measurement_point.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                    <button class="btn btn-outline-secondary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid pt-3 p-5">
        <h3 class="text-dark">Measurement Points</h3>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('project.show') }}">Projects</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('measurement_point.show_by_project', $measurementPoint->project->id) }}">Measurement
                        Points</a>
                </li>
                <li class="breadcrumb-item"><a href="#">{{ $measurementPoint->point_name }}</a></li>
            </ol>
        </nav>
        <div class="mb-3">
            <h5 class="d-inline me-4">Measurement Point Information</h5>
            <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm" id="editProjectButton"
                onclick="openModal('updateModal')">Edit Measurement Point</button>
        </div>
        <table class="table">
            <tr>
                <th scope='row'>PJO Number</th>
                <td scope='row'>{{ $measurementPoint->project->job_number }}</td>
            </tr>
            <tr>
                <th scope='row'>Point Name</th>
                <td scope='row'>{{ $measurementPoint->point_name }}</td>
            </tr>
            <tr>
                <th scope='row'>Device Location</th>
                <td scope='row'>{{ $measurementPoint->device_location }}</td>
            </tr>
            <tr>
                <th scope='row'>Remarks</th>
                <td scope='row'>{{ $measurementPoint->remarks }}</td>
            </tr>
        </table>

        <div class='devices_table'>
        </div>

        <x-delete-confirmation-modal type='Measurement Point' />
        <x-delete-modal type='user' />
        <x-user.user-create-modal />

    </div>
</body>

<script>
    $('#selectConcentrator').select2({
        dropdownParent: $('#measurementPointCreateModal'),
        placeholder: 'Select Concentrator...'
    });

    $('#selectNoiseMeter').select2({
        dropdownParent: $('#measurementPointCreateModal'),
        placeholder: 'Select Noise Meter...'
    });

    $('#selectUpdateConcentrator').select2({
        dropdownParent: $('#measurementPointUpdateModal'),

    });
    $('#selectUpdateNoiseMeter').select2({
        dropdownParent: $('#measurementPointUpdateModal'),

    });
</script>

</html>
