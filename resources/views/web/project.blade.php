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
    @vite(['resources/scss/project.scss', 'resources/js/app.js', 'resources/js/project.js'])
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
                    <button class="btn btn-outline-secondary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-sm-5 pt-sm-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <span class="text-dark h5">Projects</span>
                </li>
            </ol>
        </nav>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" onClick="changeTab(event,'rental')">Rental
                    Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onClick="changeTab(event,'sales')">Sales Project</a>
            </li>
        </ul>

        <div class="shadow" id="example-table"></div>

        <div class="d-flex flex-row mt-3 justify-content-between">
            <button class="btn btn-light text-danger border shadow-sm" id="deleteButton"
                onclick="openModal('deleteConfirmationModal')">Delete</button>

            <div id="table_pages"></div>

            <div>
                <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm" id="editButton"
                    onclick="openModal('updateModal','update')">Edit</button>
                <button class="btn btn-primary text-light  shadow-sm" id="createButton"
                    onclick="openModal('projectcreateModal')">Create</button>
            </div>
        </div>
    </div>

    <x-project.project-create-modal />
    <x-delete-confirmation-modal type='project' />
    <x-delete-modal type='user' />
    <x-project.project-update-modal />
</body>

</html>
