import { menuSetChild, setData, menuReload } from "../menu.js";
import { newPart, partUpdate } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(partId) {
    const postData = {
        part_id: partId
    };

    fetch("handle_delete_part.php", {
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
    document.getElementById("newPart").addEventListener("click", function () {
        menuSetChild(newPart);
    });

    document.querySelectorAll('[id^=part-update]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("part_id", element.id.split("-")[2]);
            menuSetChild(partUpdate);
        });
    });

    document.querySelectorAll('[id^=part-delete]').forEach((element) => {
        element.addEventListener('click', function () {
            const partId = element.id.split("-")[2];

            createConfirmModal(
                "Supprimer une pièce",
                "Êtes-vous sûr de vouloir supprimer cette pièce ?",
                () => onDelete(partId)
            );
        });
    });
}