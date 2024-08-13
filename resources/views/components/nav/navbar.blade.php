<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img class="me-2" id="geoscan_logo" style="width: 50px;"
            src="{{ asset('image/geoscanlogo_yellow.png') }}" alt="geoscan-logo"></img>Geoscan NMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('project.admin') }}">Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('concentrator.show') }}">Concentrators</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('noise_meter.show') }}">Noise Meters</a>
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

</nav>
