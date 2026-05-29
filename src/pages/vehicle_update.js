import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    document.getElementById("submitVehicleUpdate").addEventListener("click", function (event) {
        event.preventDefault();

        const vehicleId = getData('vehicle_id');
        const vin = document.getElementById("vin").value;
        const plateNumber = document.getElementById("plate_number").value;
        const registrationDate = document.getElementById("circulation_date").value;
        const modelId = document.getElementById("model_type").value;

        const postData = {
            vehicle_id: vehicleId,
            vin: vin,
            plate_number: plateNumber,
            circulation_date: registrationDate,
            model_id: modelId
        };

        fetch("handle_update_vehicle.php", {
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