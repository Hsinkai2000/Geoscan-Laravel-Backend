import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import "air-datepicker/air-datepicker.css";

var modalType = "";
var dpMin, dpMax;

function set_tables(data) {
    var noise_meter_table = new Tabulator("#noise_meter_table", {
        layout: "fitColumns",
        data: new Array(data.noise_meter),
        placeholder: "No linked Contacts",
        columns: [
            {
                title: "Serial Number",
                field: "serial_number",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Label",
                field: "noise_meter_label",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Brand",
                field: "brand",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Last Calibration Date",
                field: "last_calibration_date",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Remarks",
                field: "remarks",
                headerSort: false,
                minWidth: 100,
            },
        ],
    });

    var concentrator_table = new Tabulator("#concentrator_table", {
        layout: "fitColumns",
        data: new Array(data.concentrator),
        placeholder: "No linked Contacts",
        columns: [
            {
                title: "Device ID",
                field: "device_id",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Label",
                field: "concentrator_label",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "CSQ",
                field: "concentrator_csq",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Handphone Number",
                field: "concentrator_hp",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Battery Voltage",
                field: "battery_voltage",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Last Communication Packet Sent",
                field: "last_communication_packet_sent",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Remarks",
                field: "remarks",
                headerSort: false,
                minWidth: 100,
            },
        ],
    });
}
function create_empty_option(select, text) {
    var defaultOption = document.createElement("option");
    defaultOption.textContent = text;
    defaultOption.selected = true;
    defaultOption.disabled = true;
    select.appendChild(defaultOption);
}

function populateConcentrator() {
    var selectConcentrator;
    var defaultConcentrator;

    selectConcentrator = document.getElementById("selectConcentrator");
    selectConcentrator.innerHTML = "";
    defaultConcentrator = window.measurementPointData.concentrator;
    document.getElementById("existing_device_id").textContent =
        defaultConcentrator
            ? `${defaultConcentrator.device_id} | ${defaultConcentrator.concentrator_label}`
            : "None Linked";
    if (!defaultConcentrator) {
        create_empty_option(selectConcentrator, "Choose Concentrator...");
    }

    const url = "http://localhost:8000/concentrators/";
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
            data = data.concentrators;

            // Create options from fetched data
            data.forEach((concentrator) => {
                const option = document.createElement("option");
                option.value = concentrator.id;
                option.textContent =
                    concentrator.device_id +
                    " | " +
                    concentrator.concentrator_label;

                if (
                    defaultConcentrator &&
                    concentrator.id == defaultConcentrator.id
                ) {
                    console.log(concentrator.id);
                    console.log(defaultConcentrator.id);
                    option.selected = true;
                }
                selectConcentrator.appendChild(option);
            });
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });
}

function populateNoiseMeter() {
    var selectNoiseMeter;
    var defaultNoiseMeter;

    selectNoiseMeter = document.getElementById("selectNoiseMeter");
    selectNoiseMeter.innerHTML = "";
    defaultNoiseMeter = window.measurementPointData.noise_meter;
    document.getElementById("existing_serial").textContent = defaultNoiseMeter
        ? `${defaultNoiseMeter.serial_number} | ${defaultNoiseMeter.noise_meter_label}`
        : "None linked";
    if (!defaultNoiseMeter) {
        create_empty_option(selectNoiseMeter, "Choose Noise Meter...");
    }

    const url = "http://localhost:8000/noise_meters";
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
            data = data.noise_meters;

            data.forEach((noise_meter) => {
                const option = document.createElement("option");
                option.value = noise_meter.id;
                option.textContent =
                    noise_meter.serial_number +
                    " | " +
                    noise_meter.noise_meter_label;
                if (
                    defaultNoiseMeter &&
                    noise_meter.id == defaultNoiseMeter.id
                ) {
                    option.selected = true;
                }

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

function populateData() {
    console.log(window.measurementPointData);
    document.getElementById("inputPointName").value =
        window.measurementPointData.point_name;
    document.getElementById("inputRemarks").value =
        window.measurementPointData.remarks;
    document.getElementById("inputDeviceLocation").value =
        window.measurementPointData.device_location;
    document.getElementById("existing_devices").hidden = false;
    document.getElementById("category").innerHTML =
        window.measurementPointData.category;
}

function handle_measurementpoint_submit() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("measurement_point_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(
        "http://localhost:8000/measurement_points/" +
            window.measurementPointData.id,
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
            closeModal("measurementPointModal");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
    return false;
}

function openModal(modalName, type) {
    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();

    if (modalName == "viewPdfModal") {
        initDatePicker();
    } else if (modalName == "measurementPointModal") {
        modalType = "update";
        populateSelects();
        populateData();
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

function initDatePicker() {
    document.getElementById("start_date").value = null;
    document.getElementById("end_date").value = null;
    dpMin = new AirDatepicker("#start_date", {
        autoClose: true,
        dateFormat: "dd-MM-yyyy",
        container: "#viewPdfModal",
        locale: localeEn,
        onSelect({ date }) {
            dpMax.update({
                minDate: date,
            });
            dpMax.show();
        },
    });

    dpMax = new AirDatepicker("#end_date", {
        autoClose: true,
        dateFormat: "dd-MM-yyyy",
        container: "#viewPdfModal",
        locale: localeEn,
        onSelect({ date }) {
            dpMin.update({
                maxDate: date,
            });
        },
    });
}

async function openPdf() {
    var select_start_date = document.getElementById("start_date");
    var select_end_date = document.getElementById("end_date");

    const newTab = window.open(
        "http://localhost:8000/pdf/" +
            new URLSearchParams({
                id: window.measurementPointData.id,
                start_date: select_start_date.value,
                end_date: select_end_date.value,
            }).toString(),
        "Report"
    );
    newTab.focus();
    closeModal("viewPdfModal");
}

window.openPdf = openPdf;
window.openModal = openModal;
window.openSecondModal = openSecondModal;
window.set_tables = set_tables;
window.handle_measurementpoint_submit = handle_measurementpoint_submit;
