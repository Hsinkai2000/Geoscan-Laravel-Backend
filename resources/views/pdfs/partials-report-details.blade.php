<div>
    <br />
    <br />
    <table class="table-bordered">
        <tr>
            <th scope='row'>PJO Number</th>
            <td scope='row'>{{ $measurementPoint->project->job_number }}</td>
        </tr>
        <tr>
            <th scope='row'>Client</th>
            <td scope='row'>{{ $measurementPoint->project->client_name }}</td>

        </tr>
        <tr>
            <th scope='row'>Location</th>
            <td scope='row'>{{ $measurementPoint->project->jobsite_location }}</td>
        </tr>
        <tr>
            <th scope='row'>Project Description</th>
            <td scope='row'>{{ $measurementPoint->project->project_description }}</td>
        </tr>
        <tr>
            <th scope='row'>BCA Reference Number</th>
            <td scope='row'>{{ $measurementPoint->project->bca_reference_number }}</td>
        </tr>
        <tr>
            <th scope='row'>SMS Alerts</th>
            <td scope='row'>{{ $measurementPoint->project->sms_count }}</td>
        </tr>
        <tr>
            <th scope='row'>Status</th>
            <td scope='row'>{{ $measurementPoint->project->status }}</td>
        </tr>
    </table>
    <hr>
    <br />
    <h3>Contacts:</h3>
    <table class="table-bordered w-100">
        <tr>
            <th>Name</th>
            <th>Designation</th>
            <th>Email</th>
            <th>SMS</th>
        </tr>

        @foreach ($contacts as $contact)
            <tr>
                <td>{{ $contact->contact_person_name }}</td>
                <td>{{ $contact->designation }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->phone_number }}</td>
            </tr>
        @endforeach

    </table>
    <hr>
    <br />
    <h3>Measurement Point Details:</h3>
    <table class="table-bordered w-100">
        <tr>
            <th>Device ID</th>
            <th>Serial No.</th>
            <th>Brand</th>
            <th>Last Calibration Date</th>
            <th>Remarks</th>
            <th>Category</th>
            <th>Device Location</th>
        </tr>

        <tr>
            <td>{{ $measurementPoint->concentrator->device_id }}</td>
            <td>{{ $measurementPoint->noiseMeter->serial_number }}</td>
            <td>{{ $measurementPoint->noiseMeter->brand }}</td>
            <td>{{ $measurementPoint->noiseMeter->last_calibration_date }}</td>
            <td>{{ $measurementPoint->remarks }}</td>
            <td>{{ $measurementPoint->soundLimit->category }}</td>
            <td>{{ $measurementPoint->device_location }}</td>
        </tr>
    </table>
</div>
