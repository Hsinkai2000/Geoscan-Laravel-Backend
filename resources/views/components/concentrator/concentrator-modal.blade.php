<div class="modal fade shadow" id="concentratorModal" tabindex="-1" aria-labelledby="concentratorLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="concentratorLabel">Concentrator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='concentrator_form'>
                    @csrf
                    <div>
                        <h4>Concentrator</h4>
                        <div class="mb-3 row">
                            <label for="serial_number"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Device Id</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputdevice_id" name="device_id"
                                    minlength="16" maxlength="16" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="concentrator_label" class="col-md-3 col-sm-12 text-align-center col-form-label">
                                Label</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputLabel" name="concentrator_label"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="concentrator_csq" class="col-md-3 col-sm-12 text-align-center col-form-label">
                                CSQ</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="number" class="form-control" id="inputCSQ" name="concentrator_csq"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="concentrator_hp"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Handphone Number</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="number" class="form-control" id="inputHp" name="concentrator_hp"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="battery_voltage"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Battery Voltage</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="number" class="form-control" id="inputVoltage" name="battery_voltage"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="last_communication_packet_sent"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Last
                                Communication Packet Sent</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="date" class="form-control" id="inputLastCommunicationPacketSent"
                                    name="last_communication_packet_sent" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="last_assigned_ip_address"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Last
                                Assigned Ip Address</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputLastAssignedIpAddress"
                                    name="last_assigned_ip_address" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="remarks"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Remarks</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary bg-white text-primary"
                            data-bs-dismiss="modal">Discard</button>
                        <button type='submit' onclick="handle_concentrator_submit(event)"
                            class="btn btn-primary text-white">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
