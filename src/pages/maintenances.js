import { menuSetChild, setData, menuReload } from "../menu.js";
import { seeMaintenance, searchMaintenance, maintenanceUpdate, maintenancesRecModelTypeAdd, maintenancesRecModelAdd, maintenancesRecModelUpdate, maintenancesRecModelTypeUpdate } from "../logic.js";
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

function onRecurrentMaintenanceDelete(maintenanceId) {
    const postData = {
        maintenance_id: maintenanceId
    };

    fetch("handle_delete_recurrent_maintenance.php", {
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
    document.getElementById("searchMaintenances").addEventListener("click", function () {
        event.preventDefault();

        setData("start_date", document.getElementById("start_date").value);
        setData("end_date", document.getElementById("end_date").value);
        menuSetChild(searchMaintenance);
    });
    document.getElementById("newRecMaintenanceModelType").addEventListener("click", function () {
        menuSetChild(maintenancesRecModelTypeAdd);
    });
    document.getElementById("newRecMaintenanceModel").addEventListener("click", function () {
        menuSetChild(maintenancesRecModelAdd);
    });

    document.getElementById("ongoing-maintenance-list").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(seeMaintenance);
            });
        } else if (button.id.startsWith("maintenance-update-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(maintenanceUpdate);
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

    document.getElementById("coming-maintenance-list").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(seeMaintenance);
            });
        } else if (button.id.startsWith("maintenance-update-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(maintenanceUpdate);
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

    document.getElementById("finished-maintenance-list").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(seeMaintenance);
            });
        } else if (button.id.startsWith("maintenance-update-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(maintenanceUpdate);
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

    document.getElementById("type-model-rec-maintenance-list").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-rec-model-type-delete-")) {
            button.addEventListener("click", function () {
                const maintenanceId = button.id.split("-")[5];

                createConfirmModal(
                    "Supprimer une maintenance récurrente",
                    "Êtes-vous sûr de vouloir supprimer cette maintenance récurrente ?",
                    () => onRecurrentMaintenanceDelete(maintenanceId)
                );
            });
        } else if (button.id.startsWith("maintenance-rec-model-type-update-")) {
            button.addEventListener("click", function () {
                const maintenanceId = button.id.split("-")[5];
                setData("maintenance_id", maintenanceId);
                menuSetChild(maintenancesRecModelTypeUpdate);
            });
        }
    });

    document.getElementById("model-rec-maintenance-list").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-rec-model-delete-")) {
            button.addEventListener("click", function () {
                const maintenanceId = button.id.split("-")[4];

                createConfirmModal(
                    "Supprimer une maintenance récurrente",
                    "Êtes-vous sûr de vouloir supprimer cette maintenance récurrente ?",
                    () => onRecurrentMaintenanceDelete(maintenanceId)
                );
            });
        } else if (button.id.startsWith("maintenance-rec-model-update-")) {
            button.addEventListener("click", function () {
                const maintenanceId = button.id.split("-")[4];
                setData("maintenance_id", maintenanceId);
                menuSetChild(maintenancesRecModelUpdate);
            });
        }
    });
}
