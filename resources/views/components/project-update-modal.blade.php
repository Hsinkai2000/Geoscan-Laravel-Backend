<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectcreateLabel">Create Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="PATCH" id='updateProjectForm'>
                    <div>
                        <div class="mb-3 row">
                            <label for="job_number" class="col-md-3 col-sm-12 text-align-center col-form-label">Job
                                Number</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputupdatejobnumber" name="job_number">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="client_name" class="col-md-3 col-sm-12 text-align-center col-form-label">Client
                                name
                            </label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputUpdateClientName" name="client_name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="project_description"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Project
                                Description</label>
                            <div class="col-sm-8 align-content-center">
                                <textarea name='project_description' type="text" class="form-control"
                                    id="inputUpdateProjectDescription"></textarea>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label for="jobsite_location"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Jobsite
                                Location</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputUpdateJobsiteLocation"
                                    name='jobsite_location'>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bca_reference_number"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">BCA
                                Reference
                                Number</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputUpdateBcaReferenceNumber"
                                    name='bca_reference_number'>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="project_type"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Project
                                Type</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="project_type"
                                    id="projectUpdateTypeRental" value="rental" onchange="toggleEndUserName()" checked>
                                <label class="form-check-label" for="project_type">Rental</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="project_type"
                                    id="projectUpdateTypeSales" value="sales" onchange="toggleEndUserName()">
                                <label class="form-check-label" for="project_type">Sales</label>
                            </div>
                        </div>
                        <div class="mb-3 row" id="endUserNameDiv">
                            <label for="end_user_name" class="col-md-3 col-sm-12 text-align-center col-form-label">End
                                User Name</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputUpdateEndUserName" name='end_user_name'
                                    value="">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="user_id"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">User</label>
                            <div class="col-sm-8 align-content-center">
                                <select id="inputUpdateUserSelect" class="form-select" name="user_id">

                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary bg-white text-primary"
                            data-bs-dismiss="modal">Discard</button>
                        <button type="submit" onclick="handleUpdate()"
                            class="btn btn-primary text-white">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>