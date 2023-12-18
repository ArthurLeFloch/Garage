import { menuSetChild, setData, menuReload } from "../menu.js";
import { vehicleNewExternMaintenance, vehicleNewMaintenance, vehicleMaintenance, vehicleUpdateMaintenance, vehicleExternMaintenance, vehicleUpdateExternMaintenance, vehicleNewPrescription } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onMaintenanceDelete(maintenanceId) {
    const postData = {
        maintenance_id: maintenanceId
    };

    fetch("handle_delete_maintenance.php", {
        method: "POST",
        body: JSON.stringify(postData),
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.text())
        .then(data => {
            if (data.length === 0) {
                menuReload();
            } else {
                createToast("Erreur côté serveur", data);
            }
        })
        .catch(error => console.error("Error fetching content:", error));
}

function onExternMaintenanceDelete(externMaintenanceId) {
    const postData = {
        extern_maintenance_id: externMaintenanceId
    };

    fetch("handle_delete_extern_maintenance.php", {
        method: "POST",
        body: JSON.stringify(postData),
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.text())
        .then(data => {
            if (data.length === 0) {
                menuReload();
            } else {
                createToast("Erreur côté serveur", data);
            }
        })
        .catch(error => console.error("Error fetching content:", error));
}

function onPrescriptionDelete(prescriptionId) {
    const postData = {
        prescription_id: prescriptionId
    };

    fetch("handle_delete_prescription.php", {
        method: "POST",
        body: JSON.stringify(postData),
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.text())
        .then(data => {
            if (data.length === 0) {
                menuReload();
            } else {
                createToast("Erreur côté serveur", data);
            }
        })
        .catch(error => console.error("Error fetching content:", error));
}

export function load() {
    document.getElementById("newMaintenance").addEventListener("click", function () {
        menuSetChild(vehicleNewMaintenance);
    });
    document.getElementById("newExternMaintenance").addEventListener("click", function () {
        menuSetChild(vehicleNewExternMaintenance);
    });
    document.getElementById("newPrescription").addEventListener("click", function () {
        menuSetChild(vehicleNewPrescription);
    });
    document.getElementById("maintenance-list").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(vehicleMaintenance);
            });
        } else if (button.id.startsWith("maintenance-update-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(vehicleUpdateMaintenance);
            });
        } else if (button.id.startsWith("maintenance-delete-")) {
            button.addEventListener("click", function () {
                const maintenanceId = button.id.split("-")[2];

                createConfirmModal(
                    "Supprimer une maintenance",
                    "Êtes-vous sûr de vouloir supprimer cette maintenance ?",
                    () => onMaintenanceDelete(maintenanceId)
                );
            });
        }
    });

    document.getElementById("extern-maintenance-list").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("extern-maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("extern_maintenance_id", button.id.split("-")[3]);
                menuSetChild(vehicleExternMaintenance);
            });
        } else if (button.id.startsWith("extern-maintenance-update-")) {
            button.addEventListener("click", function () {
                setData("extern_maintenance_id", button.id.split("-")[3]);
                menuSetChild(vehicleUpdateExternMaintenance);
            });
        } else if (button.id.startsWith("extern-maintenance-delete-")) {
            button.addEventListener("click", function () {
                const externMaintenanceId = button.id.split("-")[3];

                createConfirmModal(
                    "Supprimer une maintenance externe",
                    "Êtes-vous sûr de vouloir supprimer cette maintenance externe ?",
                    () => onExternMaintenanceDelete(externMaintenanceId)
                );
            });
        }
    });

    const deletePrescription = document.getElementById("prescription-list");

    if (deletePrescription != null) {
        deletePrescription.querySelectorAll("a").forEach((button) => {
            if (button.id.startsWith("prescription-delete-")) {
                button.addEventListener("click", function () {
                    const prescriptionId = button.id.split("-")[2];

                    createConfirmModal(
                        "Supprimer une prescription",
                        "Êtes-vous sûr de vouloir supprimer cette prescription ?",
                        () => onPrescriptionDelete(prescriptionId)
                    );
                });
            }
        });
    }
}
