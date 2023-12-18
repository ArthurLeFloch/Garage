import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";


export function load() {
    document.getElementById("updatePart").addEventListener("click", function (event) {
        event.preventDefault();

        const partId = getData("part_id");
        const partTypeId = getData("part_type_id");
        const partName = document.getElementById("part_name").value;
        const partReference = document.getElementById("part_reference").value;
        const unitaryPrice = document.getElementById("unitary_price").value;


        const postData = {
            part_id: partId,
            part_type_id: partTypeId,
            part_name: partName,
            part_reference: partReference,
            unitary_price: unitaryPrice
        };

        fetch("handle_update_part.php", {
            method: "POST",
            body: JSON.stringify(postData),
            headers: { "Content-Type": "application/json" }
        })
            .then(response => response.text())
            .then(data => {
                if (data.length === 0) {
                    menuBack();
                } else {
                    createToast("Erreur côté serveur", data);
                }
            })
            .catch(error => console.error("Error fetching content:", error));

    });
}