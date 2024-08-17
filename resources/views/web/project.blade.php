<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | Main</title>

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

    @vite(['resources/scss/project.scss', 'resources/js/app.js', 'resources/js/project.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <x-nav.navbar />

    <div class="container-fluid pt-3 p-5">
        <h3 class="text-dark">Project</h3>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if (Auth::user()->isAdmin())
                    <li class="breadcrumb-item"><a href="{{ route('project.admin') }}">Projects</a></li>
                @endif
                <li class="breadcrumb-item"><a href="#">{{ $project['job_number'] }}</a></li>
            </ol>
        </nav>
        <div class="mb-3">
            <h5 class="d-inline me-4">Project Information</h5>
        </div>
        <table class="table">
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
                <th scope='row'>No. of Contacts</th>
                <td scope='row'>{{ $project['sms_count'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Status</th>
                <td scope='row'>{{ $project['status'] }}</td>
            </tr>
        </table>

        <div class="bg-light p-2 mb-3 shadow rounded">
            <div>
                <h5 class="d-inline">Contacts | </h5>
                <h6 class="d-inline @if (count($project->contact) == $project['sms_count']) text-danger  s @endif">
                    {{ count($project->contact) }} / {{ $project['sms_count'] }}</h6>
            </div>
            <div class="mt-2 mb-3 ">
                <button class="d-inline btn btn-primary text-light shadow-sm" id="createContactButton"
                    onclick='openModal("contactModal","create")'>Add</button>
                <button class="d-inline btn btn-dark text-light shadow-sm"ß id="editContactButton"
                    onclick='openModal("contactModal","update")'>Edit</button>
                <button class="btn btn-light text-danger border shadow-sm" id="deleteContactButton"
                    onclick='openModal("deleteConfirmationModal","contact")'>Delete</button>
            </div>
            <div class="shadow" id="contacts_table"></div>
        </div>
        <div class="rounded bg-light p-2 shadow">
            <h5>Measurement Points Information</h5>
            <div id="measurement_point_table"></div>

            <div class="d-flex flex-row mt-3 justify-content-between">
                <button class="btn btn-light text-danger border shadow-sm" id="deleteButton"
                    onclick="openModal('deleteConfirmationModal','delete')">Delete</button>
                <div id="measurement_point_pages"></div>
                <div>
                    <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm" id="editButton"
                        onclick='openModal("measurementPointModal", "update")'>Edit</button>
                    <button class="btn btn-primary text-light shadow-sm" id="createButton"
                        onclick='openModal("measurementPointModal", "measurementPoint")'>Create</button>
                </div>
            </div>
        </div>

        <x-contacts.contact-modal />
        <x-delete-confirmation-modal />
        <x-delete-modal type='user' />
        <x-user.user-create-modal />
        <x-measurementPoint.measurement-point-modal :project="$project" />
        <input hidden id="inputprojectId" value="{{ $project['id'] }}">
    </div>
</body>

<script>
    $('#selectConcentrator').select2({
        dropdownParent: $('#measurementPointModal'),
        placeholder: 'Select Concentrator...'
    });

    $('#selectNoiseMeter').select2({
        dropdownParent: $('#measurementPointModal'),
        placeholder: 'Select Noise Meter...'
    });

    $('#selectUpdateConcentrator').select2({
        dropdownParent: $('#measurementPointModal'),

    });
    $('#selectUpdateNoiseMeter').select2({
        dropdownParent: $('#measurementPointModal'),
    });
    window.project = @json($project);
    window.contacts = @json($project->contact);
    window.admin = @json(Auth::user()->isAdmin());
</script>

</html>
