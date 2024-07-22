import AirDatepicker from "air-datepicker";
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

function populateConcentrator() {
    var selectConcentrator = null;
    const defaultOption = document.createElement("option");

    selectConcentrator = document.getElementById("selectUpdateConcentrator");
    selectConcentrator.innerHTML = "";
    const defaultConcentrator = window.measurementPointData.concentrator;
    document.getElementById("existing_update_device_id").textContent =
        defaultConcentrator != null
            ? `${defaultConcentrator.device_id} | ${defaultConcentrator.concentrator_label}`
            : "None Linked";
    defaultOption.value =
        defaultConcentrator != null ? defaultConcentrator.id : null;
    defaultOption.textContent =
        defaultConcentrator != null
            ? `${defaultConcentrator.device_id} | ${defaultConcentrator.concentrator_label}`
            : "Select Concentrator...";
    defaultOption.disabled = defaultConcentrator != null ? false : true;

    defaultOption.selected = true;
    selectConcentrator.appendChild(defaultOption);

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
    var defaultNoiseMeter = null;
    const defaultOption = document.createElement("option");
    var selectNoiseMeter = document.getElementById("selectUpdateNoiseMeter");
    selectNoiseMeter.innerHTML = "";
    defaultNoiseMeter = window.measurementPointData.noise_meter;
    document.getElementById("existing_update_serial").textContent =
        defaultNoiseMeter != null
            ? `${defaultNoiseMeter.serial_number} | ${defaultNoiseMeter.noise_meter_label}`
            : "None linked";
    defaultOption.value =
        defaultNoiseMeter != null ? defaultNoiseMeter.id : null;
    defaultOption.textContent =
        defaultNoiseMeter != null
            ? `${defaultNoiseMeter.serial_number} | ${defaultNoiseMeter.noise_meter_label}`
            : "Select Noise Meter...";
    defaultOption.disabled = defaultNoiseMeter != null ? false : true;
    defaultOption.selected = true;
    selectNoiseMeter.appendChild(defaultOption);

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
                console.log("noise_meter");
                console.log(noise_meter);
                const option = document.createElement("option");
                option.value = noise_meter.id;
                option.textContent =
                    noise_meter.serial_number +
                    " | " +
                    noise_meter.noise_meter_label;
                console.log(defaultNoiseMeter);
                if (
                    defaultNoiseMeter &&
                    defaultNoiseMeter.noise_meter_id == noise_meter.id
                ) {
                    console.log("in if");
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
    console.log("populateData");
    document.getElementById("inputUpdatePointName").value =
        window.measurementPointData.point_name;
    document.getElementById("inputUpdateRemarks").value =
        window.measurementPointData.remarks;
    document.getElementById("inputUpdateDeviceLocation").value =
        window.measurementPointData.device_location;
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
            closeModal("measurementPointUpdateModal");
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

    if (modalName == "viewPdfModal") {
        initDatePicker();
    } else if (modalName == "measurementPointCreateModal") {
        modalType = "create";
        populateSelects();
    } else if (modalName == "measurementPointUpdateModal") {
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
        dateFormat: "dd-M-yyyy",
        container: "#viewPdfModal",
        onSelect({ date }) {
            dpMax.update({
                minDate: date,
            });
            dpMax.show();
        },
    });

    dpMax = new AirDatepicker("#end_date", {
        autoClose: true,
        dateFormat: "dd-M-yyyy",
        container: "#viewPdfModal",
        onSelect({ date }) {
            dpMin.update({
                maxDate: date,
            });
        },
    });
}

function openPdf() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("viewPdfForm");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
        console.log("asdasd" + key + value);
    });

    fetch("http://localhost:8000/pdf/" + window.measurementPointData.id, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/pdf",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.blob();
        })
        .then((blob) => {
            const url = window.URL.createObjectURL(blob);
            const newTab = window.open(url, "_blank");
            newTab.focus();
            closeModal("viewPdfModal");
        })
        .catch((error) => console.error("Error:", error));
}

window.openPdf = openPdf;
window.openModal = openModal;
window.openSecondModal = openSecondModal;
window.set_tables = set_tables;
window.handle_measurement_point_update = handle_measurement_point_update;
