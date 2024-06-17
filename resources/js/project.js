function settable(tabledata, project_type) {
    if (project_type == "rental") {
        var table = new Tabulator("#example-table", {
            pagination: "local",
            data: tabledata,
            placeholder: "Not authorised",
            paginationSize: 10,
            paginationCounter: "rows",
            columns: [
                {
                    title: "PJO Number",
                    field: "job_number",
                    headerFilter: "input",
                    frozen: true,
                },
                {
                    title: "Client Name",
                    field: "client_name",
                    headerFilter: "input",
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    headerFilter: "input",
                },
                {
                    title: "Project Description",
                    field: "project_description",
                    width: 100,
                    headerSort: false,
                },
                {
                    title: "BCA Reference Number",
                    field: "bca_reference_number",
                    headerSort: false,
                },
                {
                    title: "SMS Contacts (Number of alerts)",
                    field: "",
                    headerSort: false,
                },
                {
                    title: "Status (Ongoing/Completed)",
                    field: "status",
                    editor: "list",
                    editorParams: {
                        //Value Options (You should use ONE of these per editor)
                        values: ["", "Ongoing", "Completed"],
                        clearable: true,
                    },
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
                },
            ],
        });
    } else {
        var table = new Tabulator("#example-table", {
            pagination: "local",
            data: tabledata,
            placeholder: "Not authorised",
            paginationSize: 10,
            paginationCounter: "rows",
            dataTree: true,
            dataTreeStartExpanded: true,
            columns: [
                {
                    title: "Name",
                    field: "name",
                    headerFilter: "input",
                    frozen: true,
                    responsive: 0,
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    headerFilter: "input",
                },
                {
                    title: "Project Description",
                    field: "project_description",

                    headerSort: false,
                },
                {
                    title: "BCA Reference Number",
                    field: "bca_reference_number",
                    headerSort: false,
                },
                {
                    title: "Created At",
                    field: "created_at",
                },
            ],
        });
    }
}

var tabledata = null;

fetch("http://localhost:8000/projects", {
    method: "post",
    headers: {
        "Content-type": "application/json; charset=UTF-8",
        "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
    },
    body: JSON.stringify({
        project_type: "rental",
    }),
})
    .then((response) => {
        if (!response.ok) {
            settable(tabledata, "rental");
            throw new Error("User not Authorised");
        }
        return response.json();
    })
    .then((json) => {
        console.log(json.projects);
        tabledata = json.projects;
        settable(tabledata, "rental");
    });
