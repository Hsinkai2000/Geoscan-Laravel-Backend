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
    @vite(['resources/scss/pdf.scss', 'resources/js/pdf.js', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

@for ($date = \Carbon\Carbon::parse($start_date); $date->lte(\Carbon\Carbon::parse($end_date)); $date->addDay())

    <body>
        <div class="container mt-3">
            <div class="text-center">
                <h1>Noise Data</h1>
                <h3>Noise Device ID: {{ $measurementPoint->noiseMeter->serial_number }}</h3>
                <h3>Date: date</h3>
            </div>
            <div class="p-2 rounded border">
                <br />
                @include('pdfs.partials-report-details', [
                    'measurementPoint' => $measurementPoint,
                    'contacts' => $contacts,
                ])
                <br />
                @include('pdfs.partials-report-data', [
                    'measurementPoint' => $measurementPoint,
                    'contacts' => $contacts,
                ])
            </div>
        </div>
    </body>
    <footer>
        Geoscan Data Tracking System
    </footer>
    @pageBreak
@endfor

</html>
