function settable(tabledata, project_type) {
    document.getElementById("table_pages").innerHTML = "";

    // If a table already exists, destroy it
    if (window.table) {
        window.table.destroy();
    }
    if (project_type == "rental") {
        var table = new Tabulator("#example-table", {
            pagination: "local",
            layout: "fitColumns",
            data: tabledata,
            placeholder: "Not authorised",
            paginationSize: 20,
            paginationCounter: "rows",
            paginationElement: document.getElementById("table_pages"),
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
                    title: "PJO Number",
                    field: "job_number",
                    headerFilter: "input",
                    minWidth: 100,
                    frozen: true,
                },
                {
                    title: "Client Name",
                    field: "client_name",
                    minWidth: 100,
                    headerFilter: "input",
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    minWidth: 100,
                    headerFilter: "input",
                },
                {
                    title: "Project Description",
                    field: "project_description",
                    minWidth: window.innerWidth * 0.25,
                    headerSort: false,
                },
                {
                    title: "BCA Reference Number",
                    field: "bca_reference_number",
                    minWidth: 100,
                    headerSort: false,
                },
                {
                    title: "SMS Contacts (Number of alerts)",
                    field: "sms_count",
                    minWidth: 100,
                    headerSort: false,
                },
                {
                    title: "Status (Ongoing/Completed)",
                    field: "status",
                    editor: "list",
                    editorParams: {
                        values: ["", "Ongoing", "Completed"],
                        clearable: true,
                    },
                    minWidth: 100,
                    headerFilter: true,
                    headerFilterParams: {
                        values: {
                            "": "select...",
                            Ongoing: "Ongoing",
                            Completed: "Completed",
                        },
                        clearable: true,
                    },
                },
                {
                    title: "Created At",
                    field: "created_at",
                    minWidth: 100,
                },
            ],
        });
    } else {
        var table = new Tabulator("#example-table", {
            pagination: "local",
            data: tabledata,
            layout: "fitColumns",
            placeholder: "Not authorised",
            paginationElement: document.getElementById("table_pages"),
            paginationSize: 20,
            paginationCounter: "rows",
            dataTree: true,
            dataTreeStartExpanded: true,
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
                    title: "Name",
                    field: "name",
                    headerFilter: "input",
                    minWidth: 100,
                    frozen: true,
                    responsive: 0,
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    minWidth: 100,
                    headerFilter: "input",
                },
                {
                    title: "Project Description",
                    field: "project_description",
                    minWidth: window.innerWidth * 0.3,
                    headerSort: false,
                },
                {
                    title: "BCA Reference Number",
                    field: "bca_reference_number",
                    headerSort: false,
                    minWidth: 100,
                },
                {
                    title: "Created At",
                    field: "created_at",
                    minWidth: 100,
                },
            ],
        });
    }
    table.on("rowClick", function (e, row) {
        window.location.href = "/measurement_point/" + row.getIndex();
    });
    table.on("rowSelectionChanged", function (data, rows) {
        if (data && data.length > 0) {
            var projectId = document.getElementById("inputprojectId");
            projectId.value = data[0].id;
        }
    });
    window.table = table;
}

var tabledata = null;

function changeTab(event, project_type) {
    document.querySelectorAll(".nav-link").forEach((tab) => {
        tab.classList.remove("active");
    });

    event.currentTarget.classList.add("active");

    fetch_data(project_type);
}

function fetch_data(project_type) {
    fetch("http://localhost:8000/projects", {
        method: "post",
        headers: {
            "Content-type": "application/json; charset=UTF-8",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            project_type: project_type,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                settable(tabledata, project_type);
                throw new Error("User not Authorised");
            }
            return response.json();
        })
        .then((json) => {
            console.log(json.projects);
            tabledata = json.projects;
            settable(tabledata, project_type);
        });
}

function fetch_users() {
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
            var select = document.getElementById("inputUserSelect");
            select.innerHTML = "";
            select.innerHTML +=
                '<option value="' + opt + '">' + "Select..." + "</option>";
            for (var i = 0; i < json.users.length; i++) {
                var opt = json.users[i];
                select.innerHTML +=
                    '<option value="' +
                    opt["id"] +
                    '">' +
                    opt["username"] +
                    "</option>";
            }
        });
}

function create_project() {
    const form = document.getElementById("projectCreateForm");
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Close the modal
                let modalElement =
                    document.getElementById("projectCreateModal");
                let modal = bootstrap.Modal.getInstance(modalElement);
                modal.hide();
                location.reload();
            } else {
                const errors = data.errors;

                document
                    .querySelectorAll(".text-danger")
                    .forEach((el) => el.remove());
                for (const key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        const inputElement = form.querySelector(
                            `[name="${key}"]`
                        );
                        const errorElement = document.createElement("span");
                        errorElement.classList.add("text-danger");
                        errorElement.textContent = errors[key][0];
                        inputElement.after(errorElement);
                    }
                }
            }
        })
        .catch((error) => console.error("Error:", error));
}

function toggleEndUserName() {
    var rentalRadio = document.getElementById("projectTypeRental");
    var endUserNameDiv = document.getElementById("endUserNameDiv");
    if (rentalRadio.checked) {
        endUserNameDiv.style.display = "none";
    } else {
        endUserNameDiv.style.display = "flex";
    }
}

function handleDelete() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var projectId = document.getElementById("inputprojectId").value;
    fetch("http://localhost:8000/project/" + projectId, {
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

window.handleDelete = handleDelete;
window.changeTab = changeTab;
window.fetch_data = fetch_data;
window.fetch_users = fetch_users;
window.toggleEndUserName = toggleEndUserName;

fetch_data("rental");
toggleEndUserName();
