import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";


export function load() {

    document.getElementById("updatePartType").addEventListener("click", function (event) {
        event.preventDefault();

		const partTypeId = getData("part_type_id");
        const partTypeName = document.getElementById("part_type_name").value;

        const postData = {
			part_type_id: partTypeId,
            part_type_name: partTypeName
        };

        fetch("handle_update_part_type.php", {
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