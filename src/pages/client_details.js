import { menuSetChild, setData, menuReload } from "../menu.js";
import { clientsAddVehicle, vehicleDetails, vehicleUpdate } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(vehicleId) {
    const postData = {
        vehicle_id: vehicleId
    };

    fetch("handle_delete_vehicle.php", {
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
    document.getElementById("newVehicle").addEventListener("click", function () {
        menuSetChild(clientsAddVehicle);
    });

    const actionsButtons = document.getElementById("vehicle-list").querySelectorAll("a");
    actionsButtons.forEach((button) => {
        if (button.id.startsWith("vehicle-details-")) {
            button.addEventListener("click", function () {
                setData("vehicle_id", button.id.split("-")[2]);
                menuSetChild(vehicleDetails);
            });
        } else if (button.id.startsWith("vehicle-update-")) {
            button.addEventListener("click", function () {
                setData("vehicle_id", button.id.split("-")[2]);
                menuSetChild(vehicleUpdate);
            });
        } else if (button.id.startsWith("vehicle-delete-")) {
            button.addEventListener("click", function () {
                const vehicleId = button.id.split("-")[2];

                createConfirmModal(
                    "Supprimer un véhicule",
                    "Êtes-vous sûr de vouloir supprimer ce véhicule ?",
                    () => onDelete(vehicleId));
            });
        }
    })
}
