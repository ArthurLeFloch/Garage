import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    document.getElementById("addVehicle").addEventListener("click", function (event) {
        event.preventDefault();

        const vin = document.getElementById("vin").value;
        const plateNumber = document.getElementById("plate_number").value;
        const registrationDate = document.getElementById("circulation_date").value;
        const modelId = document.getElementById("model_type").value;
        const clientId = getData("client_id");

        const postData = {
            vin: vin,
            plate_number: plateNumber,
            circulation_date: registrationDate,
            model_id: modelId,
            client_id: clientId
        };

        fetch("handle_add_vehicle.php", {
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