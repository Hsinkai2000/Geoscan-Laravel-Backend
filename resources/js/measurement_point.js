function set_contact_table(contactData) {
    var table = new Tabulator("#contacts_table", {
        layout: "fitColumns",
        data: contactData,
        placeholder: "Not authorised",
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
var contactData = null;
var currentUrl = window.location.href;
var urlParts = currentUrl.split("/");
var projectid = urlParts[urlParts.length - 1];

fetch("http://localhost:8000/contacts/" + projectid, {
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
        console.log(contactData);
        set_contact_table(contactData);
    });

fetch("http://localhost:8000/measurement_point/" + projectid, {
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
        measurementPoint_data = json.measurement_point;
        console.log(measurementPoint_data);
        set_measurement_point_table(measurementPoint_data);
    });
