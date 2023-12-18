import { menuSetChild, menuReload, setData } from "../menu.js";
import { newIntervention, interventionDetails, interventionUpdate } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(interventionId) {
    const postData = {
        intervention_id: interventionId
    };

    fetch("handle_delete_intervention.php", {
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
    document.getElementById("newIntervention").addEventListener("click", function () {
        menuSetChild(newIntervention);
    });

    const detailButtons = document.getElementById("intervention-list").querySelectorAll("a");
    detailButtons.forEach((button) => {
        if (button.id.startsWith("intervention-details-")) {
            button.addEventListener("click", function () {
                setData("intervention_id", button.id.split("-")[2]);
                menuSetChild(interventionDetails);
            });
        } else if (button.id.startsWith("intervention-update-")) {
            button.addEventListener("click", function () {
                setData("intervention_id", button.id.split("-")[2]);
                menuSetChild(interventionUpdate);
            });
        } else if (button.id.startsWith("intervention-delete-")) {
            button.addEventListener("click", function () {
                const interventionId = button.id.split("-")[2];

                createConfirmModal(
                    "Supprimer une intervention",
                    "Êtes-vous sûr de vouloir supprimer cette intervention ?",
                    () => onDelete(interventionId)
                );
            });
        }
    });
}