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
            },
            {
                title: "Designation",
                field: "designation",
                headerSort: false,
            },
            {
                title: "Email",
                field: "email",
                headerSort: false,
            },
            {
                title: "SMS",
                field: "phone_number",
                headerSort: false,
            },
            {
                title: "FAX",
                field: "fax_number",
                headerSort: false,
            },
        ],
    });
}

function set_measurement_point_table(measurementPoint_data) {
    var measurementPointTable = new Tabulator("#measurement_point_table", {
        layout: "fitColumns",
        data: measurementPoint_data,
        placeholder: "No Linked Measurement Points",
        columns: [
            {
                title: "Point Name",
                field: "point_name",
            },
            {
                title: "Point Location",
                field: "device_location",
            },
            {
                title: "Concentrator Label",
                field: "concentrator_label",
            },
            {
                title: "Concentrator Serial",
                field: "device_id",
            },
            {
                title: "Concentrator Battery Voltage",
                field: "battery_voltage",
            },
            {
                title: "Concentrator CSQ",
                field: "concentrator_csq",
            },
            {
                title: "Last Concentrator Communication",
                field: "last_communication_packet_sent",
            },
            {
                title: "Noise Label",
                field: "noise_meter_label",
            },
            {
                title: "Noise Serial",
                field: "serial_number",
            },
            {
                title: "Data Status",
                field: "leq",
            },
            {
                title: "Last Data Sent",
                field: "received_at",
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

function handleUpdate() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var projectId = document.getElementById("inputprojectId");
    projectId = projectId.value;
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

window.fetch_users = fetch_users;
window.handleUpdate = handleUpdate;

get_contact_data();
get_measurement_point_data();
