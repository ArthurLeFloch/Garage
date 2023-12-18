import { menuSetChild, setData, menuReload } from "../menu.js";
import { newManufacturer, manufacturerUpdate, manufacturerDetails } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(manufacturerId) {
    const postData = {
        manufacturer_id: manufacturerId
    };

    fetch("handle_delete_manufacturer.php", {
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
    document.querySelectorAll('[id^=manufacturer-details]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("manufacturer_id", element.id.split("-")[2]);
            menuSetChild(manufacturerDetails);
        });
    });

    document.querySelectorAll('[id^=manufacturer-update]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("manufacturer_id", element.id.split("-")[2]);
            menuSetChild(manufacturerUpdate);
        });
    });

    document.querySelectorAll('[id^=manufacturer-delete]').forEach((element) => {
        element.addEventListener('click', function () {
            const manufacturerId = element.id.split("-")[2];

            createConfirmModal(
                "Supprimer un fabricant",
                "Êtes-vous sûr de vouloir supprimer ce fabricant ?",
                () => onDelete(manufacturerId)
            );
        });
    });

    document.getElementById("newManufacturer").addEventListener("click", function () {
        menuSetChild(newManufacturer);
    });
}