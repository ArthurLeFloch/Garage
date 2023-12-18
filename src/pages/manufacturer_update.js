import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    document.getElementById("updateManufacturer").addEventListener("click", function (event) {
        event.preventDefault();

        const manufacturer_name = document.getElementById("manufacturer_name").value;
		const manufacturer_id = getData("manufacturer_id");

        const postData = {
			manufacturer_id: manufacturer_id,
            manufacturer_name: manufacturer_name
        };

        fetch("handle_update_manufacturer.php", {
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