<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | Noise Meters</title>

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

    @vite(['resources/scss/noise_meters.scss', 'resources/js/app.js', 'resources/js/noise_meters.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <x-nav.navbar />

    <div class="container-fluid pt-3 p-5">

        <div class="mb-3">
            <h3 class=" text-dark d-inline me-4">Noise Meters</h3>

        </div>
        <div class="mb-3">

            <div id='noise_meter_table'></div>
            <div class="d-flex flex-row mt-3 justify-content-between">
                <button class="btn btn-light text-danger border shadow-sm" id="deleteButton"
                    onclick="openModal('deleteConfirmationModal')">Delete</button>
                <div id="noise_meter_pages"></div>
                <div>
                    <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm" id="editButton"
                        onclick='openModal("noiseMeterModal", "update")'>Edit</button>
                    <button class="btn btn-primary text-light shadow-sm" id="createButton"
                        onclick="openModal('noiseMeterModal','create')">Create</button>
                </div>
            </div>
        </div>
    </div>

    <x-noise-meter.noise-meter-modal />
    <x-delete-confirmation-modal type='noise meter' />
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.noiseMeters = @json($noise_meters);
        set_tables(window.noiseMeters);
    });
</script>

</html>