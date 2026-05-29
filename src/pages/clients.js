import { menuSetChild, setData, menuReload } from "../menu.js";
import { clientAdd, clientDetails, clientUpdate } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(clientId) {
    const postData = {
        client_id: clientId
    };

    fetch("handle_delete_client.php", {
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
    document.getElementById("newClient").addEventListener("click", function () {
        menuSetChild(clientAdd);
    });

    const actionsButtons = document.getElementById("client-list").querySelectorAll("a");
    actionsButtons.forEach((button) => {
        if (button.id.startsWith("client-details-")) {
            button.addEventListener("click", function () {
                setData("client_id", button.id.split("-")[2]);
                menuSetChild(clientDetails);
            });
        } else if (button.id.startsWith("client-update-")) {
            button.addEventListener("click", function () {
                setData("client_id", button.id.split("-")[2]);
                menuSetChild(clientUpdate);
            });
        } else if (button.id.startsWith("client-delete-")) {
            button.addEventListener("click", function () {
                const clientId = button.id.split("-")[2];

                createConfirmModal(
                    "Supprimer un client",
                    "Êtes-vous sûr de vouloir supprimer ce client ?",
                    () => onDelete(clientId)
                );
            });
        }
    });
}

