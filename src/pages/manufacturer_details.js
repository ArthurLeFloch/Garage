import { menuSetChild, setData, menuReload } from "../menu.js";
import { newModel, modelUpdate } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(modelId) {
    const postData = {
        model_id: modelId
    };

    fetch("handle_delete_model.php", {
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
    document.querySelectorAll('[id^=model-details]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("model_id", element.id.split("-")[2]);
            menuSetChild(modelDetails);
        });
    });

    document.querySelectorAll('[id^=model-update]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("model_id", element.id.split("-")[2]);
            menuSetChild(modelUpdate);
        });
    });

    document.querySelectorAll('[id^=model-delete]').forEach((element) => {
        element.addEventListener('click', function () {
            const modelId = element.id.split("-")[2];

            createConfirmModal(
                "Supprimer un modèle",
                "Êtes-vous sûr de vouloir supprimer ce modèle ?",
                () => onDelete(modelId)
            );
        });
    });

    document.getElementById("newModel").addEventListener("click", function () {
        menuSetChild(newModel);
    });
}