var inputprojectId = null;
var userList = [];
var modalType = "";
var inputUserId = null;
var inputMeasurementPointId = null;
var noise_meter_data = [];
var concentrator_data = [];

function populateConcentrator() {
    var selectConcentrator;
    const defaultOption = document.createElement("option");

    if (modalType === "update") {
        selectConcentrator = document.getElementById(
            "selectUpdateConcentrator"
        );
        selectConcentrator.innerHTML = "";
        const defaultConcentrator = concentrator_data[0];
        defaultOption.value = defaultConcentrator.noise_meter_id;
        defaultOption.textContent = `${defaultConcentrator.device_id} | ${defaultConcentrator.concentrator_label}`;
    } else {
        selectConcentrator = document.getElementById("selectConcentrator");
        selectConcentrator.innerHTML = "";
        defaultOption.value = "";
        defaultOption.textContent = "Select Concentrator...";
        defaultOption.disabled = true;
    }
    defaultOption.selected = true;
    selectConcentrator.appendChild(defaultOption);

    const url = "http://localhost:8000/concentrators/available";
    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((data) => {
            console.log(data);
            data = data.concentrators;

            // Create options from fetched data
            data.forEach((concentrator) => {
                const option = document.createElement("option");
                option.value = concentrator.id;
                option.textContent =
                    concentrator.device_id +
                    " | " +
                    concentrator.concentrator_label;
                selectConcentrator.appendChild(option);
            });
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });
}

function populateNoiseMeter() {
    const defaultOption = document.createElement("option");

    if (modalType == "update") {
        var selectNoiseMeter = document.getElementById(
            "selectUpdateNoiseMeter"
        );
        selectNoiseMeter.innerHTML = "";
        const defaultNoiseMeter = noise_meter_data[0];

        defaultOption.value = defaultNoiseMeter.concentrator_id;
        defaultOption.textContent = `${defaultNoiseMeter.serial_number} | ${defaultNoiseMeter.noise_meter_label}`;
    } else {
        var selectNoiseMeter = document.getElementById("selectNoiseMeter");
        selectNoiseMeter.innerHTML = "";
        defaultOption.disabled = true;
        defaultOption.value = "";
        defaultOption.textContent = "Select Noise Meter...";
    }
    defaultOption.selected = true;
    selectNoiseMeter.appendChild(defaultOption);

    const url = "http://localhost:8000/noise_meters/available";
    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((data) => {
            data = data.noise_meter;

            data.forEach((noise_meter) => {
                console.log("noise_meter");
                console.log(noise_meter);
                const option = document.createElement("option");
                option.value = noise_meter.id;
                option.textContent =
                    noise_meter.serial_number +
                    " | " +
                    noise_meter.noise_meter_label;
                selectNoiseMeter.appendChild(option);
            });
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });
}

function populateSelects() {
    populateConcentrator();
    populateNoiseMeter();
}

function set_contact_table(contactData) {
    var contactTable = new Tabulator("#contacts_table", {
        layout: "fitColumns",
        data: contactData,
        placeholder: "No linked Contacts",
        columns: [
            {
                title: "Name",
                field: "contact_person_name",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Designation",
                field: "designation",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Email",
                field: "email",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "SMS",
                field: "phone_number",
                headerSort: false,
                minWidth: 100,
            },
        ],
    });
}

function set_measurement_point_table(measurementPoint_data) {
    var measurementPointTable = new Tabulator("#measurement_point_table", {
        layout: "fitColumns",
        data: measurementPoint_data,
        placeholder: "No Linked Measurement Points",
        paginationSize: 20,
        pagination: "local",
        paginationCounter: "rows",
        paginationElement: document.getElementById("measurement_point_pages"),
        selectable: 1,
        columns: [
            {
                formatter: "rowSelection",
                titleFormatter: "rowSelection",
                hozAlign: "center",
                headerSort: false,
                frozen: true,
                width: 30,
            },
            {
                title: "Point Name",
                field: "point_name",
                minWidth: 100,
                headerFilter: "input",
                frozen: true,
            },
            {
                title: "Point Location",
                field: "device_location",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator Serial",
                field: "device_id",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator Battery Voltage",
                field: "battery_voltage",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator CSQ",
                field: "concentrator_csq",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Last Concentrator Communication",
                field: "last_communication_packet_sent",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Noise Serial",
                field: "serial_number",
                minWidth: 100,
                headerFilter: "input",
            },
            {
                title: "Data Status",
                field: "data_status",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
                formatter: "tickCross",
            },
        ],
    });
    measurementPointTable.on("rowClick", function (e, row) {
        // window.location.href = "/measurement_point/" + row.getIndex();
        console.log("point clicked");
    });
    measurementPointTable.on("rowSelectionChanged", function (data, rows) {
        table_row_changed(data);
    });
    window.measurementPointTable = measurementPointTable;
}

function table_row_changed(data) {
    if (data && data.length > 0) {
        document.getElementById("editButton").disabled = false;
        document.getElementById("deleteButton").disabled = false;
        inputMeasurementPointId = data[0].id;
        fetch_measurement_point_data(data[0]);
    } else {
        document.getElementById("editButton").disabled = true;
        document.getElementById("deleteButton").disabled = true;
    }
}

function fetch_measurement_point_data(data) {
    noise_meter_data = [];
    concentrator_data = [];
    var pointName = document.getElementById("inputUpdatePointName");
    var remarks = document.getElementById("inputUpdateRemarks");
    var device_location = document.getElementById("inputUpdateDeviceLocation");

    if (data) {
        pointName.value = data.point_name;
        remarks.value = data.remarks;
        device_location.value = data.device_location;

        concentrator_data.push({
            concentrator_id: data.concentrator_id,
            concentrator_label: data.concentrator_label,
            device_id: data.device_id,
        });

        noise_meter_data.push({
            noise_meter_id: data.noise_meter_id,
            noise_meter_label: data.noise_meter_label,
            serial_number: data.serial_number,
        });

        console.log(noise_meter_data);
    }
}

function getProjectId() {
    inputprojectId = document.getElementById("inputprojectId").value;
}

function get_contact_data() {
    var contactData = null;
    fetch("http://localhost:8000/contacts/" + inputprojectId, {
        method: "get",
        headers: {
            "Content-type": "application/json; charset=UTF-8",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => {
            if (!response.ok) {
            }
            return response.json();
        })
        .then((json) => {
            contactData = json.contact;
            set_contact_table(contactData);
        });
}

function get_measurement_point_data() {
    fetch("http://localhost:8000/measurement_points/" + inputprojectId, {
        method: "get",
        headers: {
            "Content-type": "application/json; charset=UTF-8",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => {
            if (!response.ok) {
                return response.text().then((text) => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then((json) => {
            var measurementPoint_data = json.measurement_point;
            set_measurement_point_table(measurementPoint_data);
        })
        .catch((error) => {
            console.log(error);
        });
}

function create_users(projectId, csrfToken) {
    userList.forEach((user) => {
        console.log(user);
        user.project_id = projectId;
        fetch("http://localhost:8000/user/", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(user),
        }).then((response) => {
            if (response.ok) {
                console.log(user.username + "added");
            }
        });
    });
}

function handle_create_measurement_point() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("measurement_point_create_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(form.action, {
        method: form.method,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((json) => {
            closeModal("measurementPointCreateModal");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
    return false;
}

function handle_measurement_point_update() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("measurementPointUpdateForm");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(
        "http://localhost:8000/measurement_points/" + inputMeasurementPointId,
        {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(formDataJson),
        }
    )
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((json) => {
            closeModal("measurementPointUpdateModal");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
    return false;
}

function handle_create_dummy_user() {
    var inputUsername = document.getElementById("inputUsername").value;
    var inputPassword = document.getElementById("inputPassword").value;
    userList.push({
        username: inputUsername,
        password: inputPassword,
    });
    if (modalType == "update") {
        populateUser("userUpdateSelectList", inputprojectId);
    } else {
        populateUser("userselectList");
    }

    closeModal("userCreateModal");
}

function handleSelection(item) {
    inputUserId = item.id;
}

function populateUser(element) {
    window.userselectList = document.getElementById(element);
    if (inputprojectId) {
        fetch("http://localhost:8000/users/" + inputprojectId)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                populateList(data.users);
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });
    } else {
        populateList([]);
    }
}

function populateList(data) {
    window.userselectList.innerHTML = "";

    let selectedListItem = null;
    if (userList != []) {
        userList.forEach((user) => {
            data.push(user);
        });
    }
    console.log(data);
    data.forEach((item) => {
        const listItem = document.createElement("div");
        listItem.textContent = item.username;
        listItem.className = "list-item";

        listItem.addEventListener("click", function () {
            handleSelection(item);

            if (selectedListItem) {
                selectedListItem.classList.remove("selected");
            }

            listItem.classList.add("selected");

            selectedListItem = listItem;
        });

        window.userselectList.appendChild(listItem);
    });
}

function deleteUser(event) {
    if (event) {
        event.preventDefault();
    }
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch("http://localhost:8000/users/" + inputUserId, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => {
            if (!response.ok) {
                console.log("Error:", response);
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            if (modalType == "update") {
                populateUser("userUpdateSelectList", inputprojectId);
            } else {
                populateUser("userselectList", inputprojectId);
            }

            // Close the modal
            const modalElement = document.getElementById("deleteModal");
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function handleDelete(event) {
    if (event) {
        event.preventDefault();
    }
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    var confirmation = document.getElementById("inputDeleteConfirmation").value;
    if (confirmation == "DELETE") {
        fetch(
            "http://localhost:8000/measurement_points/" +
                inputMeasurementPointId,
            {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            }
        )
            .then((response) => {
                if (!response.ok) {
                    console.log("Error:", response);
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log("Success:", data);
                closeModal("deleteConfirmationModal");
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    } else {
        var error = document.getElementById("deleteConfirmationError");
        error.hidden = false;
    }
}

function handleUpdate() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("updateProjectForm");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch("http://localhost:8000/project/" + inputprojectId, {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((json) => {
            create_users(inputprojectId, csrfToken);
            closeModal("updateModal");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
    return false;
}

function openModal(modalName) {
    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();

    if (modalName == "updateModal") {
        userList = [];
        modalType = "update";
        populateUser("userUpdateSelectList");
    } else if (modalName == "measurementPointCreateModal") {
        modalType = "create";
        populateSelects();
    } else if (modalName == "measurementPointUpdateModal") {
        modalType = "update";
        populateSelects();
    }
}

function openSecondModal(initialModal, newModal) {
    var firstModalEl = document.getElementById(initialModal);
    var firstModal = bootstrap.Modal.getInstance(firstModalEl);

    firstModal.hide();

    firstModalEl.addEventListener(
        "hidden.bs.modal",
        function () {
            var secondModal = new bootstrap.Modal(
                document.getElementById(newModal)
            );

            if (newModal == "userCreateModal") {
                document.getElementById("inputUsername").value = "";
                document.getElementById("inputPassword").value = "";
            }
            secondModal.show();

            document.getElementById(newModal).addEventListener(
                "hidden.bs.modal",
                function () {
                    firstModal.show();
                },
                { once: true }
            );
        },
        { once: true }
    );
}

function closeModal(modal) {
    // Close the modal
    const modalElement = document.getElementById(modal);
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
    location.reload();
}

window.handle_measurement_point_update = handle_measurement_point_update;
window.handle_create_measurement_point = handle_create_measurement_point;
window.handleDelete = handleDelete;
window.handleUpdate = handleUpdate;
window.openModal = openModal;
window.openSecondModal = openSecondModal;
window.deleteUser = deleteUser;
window.handle_create_dummy_user = handle_create_dummy_user;

getProjectId();
get_contact_data();
get_measurement_point_data();
