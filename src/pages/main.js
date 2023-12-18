import { menuSetChild, setData, menuReload, menuSetRoot } from "../menu.js";
import { seeMaintenanceFromMenu, updateMaintenanceFromMenu, clientsMenu, clientDetails, vehicleDetails } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(maintenanceId) {
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

export function load() {
    document.getElementById("upcoming-maintenance").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(seeMaintenanceFromMenu);
            });
        } else if (button.id.startsWith("maintenance-update-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(updateMaintenanceFromMenu);
            });
        } else if (button.id.startsWith("maintenance-delete-")) {
            button.addEventListener("click", function () {
                const maintenanceId = button.id.split("-")[2];

                createConfirmModal(
                    "Supprimer une maintenance",
                    "Êtes-vous sûr de vouloir supprimer cette maintenance ?",
                    () => onDelete(maintenanceId)
                );
            });
        }
    });

    document.getElementById("ongoing-maitenance").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(seeMaintenanceFromMenu);
            });
        } else if (button.id.startsWith("maintenance-update-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                menuSetChild(updateMaintenanceFromMenu);
            });
        } else if (button.id.startsWith("maintenance-delete-")) {
            button.addEventListener("click", function () {
                const maintenanceId = button.id.split("-")[2];

                createConfirmModal(
                    "Supprimer une maintenance",
                    "Êtes-vous sûr de vouloir supprimer cette maintenance ?",
                    () => onDelete(maintenanceId)
                );
            });
        }
    });

    document.getElementById("maintenance-to-plan").querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("vehicule-link-")) {
            button.addEventListener("click", function () {
                // This raises an error, as pages does not load fully, except for the last one
                menuSetRoot(clientsMenu);
                setData("client_id", button.id.split("-")[3]);

                menuSetChild(clientDetails);
                setData("vehicle_id", button.id.split("-")[2]);
                menuSetChild(vehicleDetails);
            });
        }
    });


}