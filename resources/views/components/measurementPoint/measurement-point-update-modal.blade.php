<div class="modal fade shadow" id="measurementPointUpdateModal" tabindex="-1" aria-labelledby="measurementPointUpdateModal"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="measurementpointUpdatelabel">Edit Measurement Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='measurementPointUpdateForm' method="PATCH">
                    @csrf
                    <div>
                        <div class="mb-3 row">
                            <label for="point_name"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Measurement Point
                                Name</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputUpdatePointName" name="point_name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="remarks"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Remarks</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputUpdateRemarks" name="remarks">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="device_location"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Device Location</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputUpdateDeviceLocation"
                                    name="device_location">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="user_id"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Concentrator</label>
                            <div class="col-sm-8 align-content-center">
                                <select id="selectUpdateConcentrator" name='concentrator_id'>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="user_id" class="col-md-3 col-sm-12 text-align-center col-form-label">Noise
                                Meter</label>
                            <div class="col-sm-8 align-content-center">
                                <select id="selectUpdateNoiseMeter" name='noise_meter_id'>
                                </select>
                            </div>
                        </div>

                        <input hidden name='project_id' value="{{ $project['id'] }}" />
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary bg-white text-primary"
                            data-bs-dismiss="modal">Discard</button>
                        <button type='button' onclick="handle_measurement_point_update()"
                            class="btn btn-primary text-white">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
