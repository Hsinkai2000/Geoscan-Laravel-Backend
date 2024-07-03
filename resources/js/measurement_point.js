var inputprojectId = null;
var userList = [];
var modalType = "";
var inputUserId = null;

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

function handleDelete() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    fetch("http://localhost:8000/measurement_points/" + inputprojectId, {
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
            // location.reload() should only be called after successful operations
            location.reload();
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
    } else if (modalName == "projectcreateModal") {
        userList = [];
        inputprojectId = "";
        modalType = "create";
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
}

window.handleDelete = handleDelete;
window.handleUpdate = handleUpdate;
window.openModal = openModal;
window.openSecondModal = openSecondModal;
window.deleteUser = deleteUser;
window.handle_create_dummy_user = handle_create_dummy_user;

getProjectId();
get_contact_data();
get_measurement_point_data();
