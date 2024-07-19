<div class="modal fade shadow" id="measurementPointCreateModal" tabindex="-1" aria-labelledby="projectcreateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectcreateLabel">Create Measurement Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='measurement_point_create_form' action="{{ route('measurement_point.create') }}"
                    method="POST">
                    @csrf
                    <div>
                        <h4>Edit Measurement Point</h4>
                        <div class="mb-3 row">
                            <label for="point_name"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Measurement Point
                                Name</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputPointName" name="point_name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="remarks"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Remarks</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="device_location"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Device Location</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputDeviceLocation"
                                    name="device_location">
                            </div>
                        </div>
                        </br>
                        <hr>
                        </br>
                        <h4>Link Devices</h4>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <div class="col">
                                    Concentrator
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col">
                                    Noise Meter
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row" id="existing_devices" hidden>
                            <div class="col-6">
                                <div class="col">
                                    Existing Device Id:
                                </div>
                                <div class="col">
                                    <span id="existing_device_id"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col">
                                    Existing Noise Meter
                                </div>
                                <div class="col">
                                    <span id="existing_serial"></span>
                                </div>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <div class="col-6">
                                <div class="col">
                                    New Device Id:
                                </div>
                                <div class="col">
                                    <select id="selectConcentrator" name="concentrator_id" style="width: 80%">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col">
                                    New Noise Meter
                                </div>
                                <div class="col">
                                    <select id="selectNoiseMeter" name="noise_meter_id" style="width: 80%">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <input hidden name='project_id' value="{{ $project['id'] }}" />
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary bg-white text-primary"
                            data-bs-dismiss="modal">Discard</button>
                        <button type='button' onclick="handle_create_measurement_point()"
                            class="btn btn-primary text-white">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
