import { menuSetChild, setData, menuReload } from "../menu.js";
import { newModelType, modelTypeUpdate } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(modelTypeId) {
    const postData = {
        model_type_id: modelTypeId
    };

    fetch("handle_delete_model_type.php", {
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
    document.getElementById("newModelType").addEventListener("click", function () {
        menuSetChild(newModelType);
    });

    document.querySelectorAll('[id^=model-type-update]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("model_type_id", element.id.split("-")[3]);
            menuSetChild(modelTypeUpdate);
        });
    });

    document.querySelectorAll('[id^=model-type-delete]').forEach((element) => {
        element.addEventListener('click', function () {
            const modelTypeId = element.id.split("-")[3];

            createConfirmModal(
                "Supprimer un type de modèle",
                "Êtes-vous sûr de vouloir supprimer ce type de modèle ?",
                () => onDelete(modelTypeId)
            );
        });
    });
}