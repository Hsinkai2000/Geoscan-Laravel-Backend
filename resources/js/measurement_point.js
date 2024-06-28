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
            {
                title: "FAX",
                field: "fax_number",
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
                title: "Concentrator Label",
                field: "concentrator_label",
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
                title: "Noise Label",
                field: "noise_meter_label",
                minWidth: 100,
                headerFilter: "input",
            },
            {
                title: "Noise Serial",
                field: "serial_number",
                minWidth: 100,
                headerFilter: "input",
            },
            {
                title: "Data Status",
                field: "leq",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Last Data Sent",
                field: "received_at",
                headerSort: false,
                minWidth: 100,
            },
        ],
    });
}

function get_contact_data() {
    var contactData = null;
    var currentUrl = window.location.href;
    var urlParts = currentUrl.split("/");
    window.projectid = urlParts[urlParts.length - 1];

    fetch("http://localhost:8000/contacts/" + window.projectid, {
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
    fetch("http://localhost:8000/measurement_points/" + window.projectid, {
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
            console.log(measurementPoint_data);
            set_measurement_point_table(measurementPoint_data);
        })
        .catch((error) => {
            console.log(error);
        });
}

function fetch_users(element, id = null) {
    console.log("fetchuser " + id);
    fetch("http://localhost:8000/users", {
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
            var select = document.getElementById(element);
            select.innerHTML = "";
            select.innerHTML += "<option>Select...</option>";
            for (var i = 0; i < json.users.length; i++) {
                var opt = json.users[i];
                var selected = opt.id === id ? "selected" : ""; // Determine if this option should be selected
                console.log(opt.id);
                console.log(selected);
                select.innerHTML +=
                    '<option value="' +
                    opt.id +
                    '" ' +
                    selected +
                    ">" +
                    opt.username +
                    "</option>";
            }
        });
}

function handleDelete() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var projectId = document.getElementById("inputprojectId").value;
    console.log("asd" + projectId);
    fetch("http://localhost:8000/measurement_points/" + projectId, {
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
            console.log("Success:", data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function handleUpdate() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var projectId = document.getElementById("inputprojectId").value;
    var form = document.getElementById("updateProjectForm");

    var formData = new FormData(form);
    console.log("formdata: " + formData);
    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });
    console.log("porjectid" + projectId);

    fetch("http://localhost:8000/project/" + projectId, {
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
                console.log("Error:", response);
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            console.log("Success:", data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });

    return false;
}

window.handleDelete = handleDelete;
window.fetch_users = fetch_users;
window.handleUpdate = handleUpdate;

get_contact_data();
get_measurement_point_data();
