import { menuSetChild, setData, menuReload } from "../menu.js";
import { newPartType, manageParts, partTypeUpdate } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(partTypeId) {
    const postData = {
        part_type_id: partTypeId
    };

    fetch("handle_delete_part_type.php", {
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
    document.getElementById("newPartType").addEventListener("click", function () {
        menuSetChild(newPartType);
    });

    document.querySelectorAll('[id^=part-type-details]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("part_type_id", element.id.split("-")[3]);
            menuSetChild(manageParts);
        });
    });

    document.querySelectorAll('[id^=part-type-update]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("part_type_id", element.id.split("-")[3]);
            menuSetChild(partTypeUpdate);
        });
    });

    document.querySelectorAll('[id^=part-type-delete]').forEach((element) => {
        element.addEventListener('click', function () {
            const partTypeId = element.id.split("-")[3];

            createConfirmModal(
                "Supprimer un type de pièce",
                "Êtes-vous sûr de vouloir supprimer ce type de pièce ?",
                () => onDelete(partTypeId)
            );
        });
    });
}