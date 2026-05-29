import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    document.getElementById("updateModel").addEventListener("click", function (event) {
        event.preventDefault();

        const modelName = document.getElementById("model-name").value;
        const modelVersion = document.getElementById("model-version").value;
        const modelTypeName = document.getElementById("model-type-name").value;
        const fuelType = document.getElementById("fuel-type").value;
        const coolantType = document.getElementById("coolant-type").value;
        const suspensionType = document.getElementById("suspension-type").value;
        const wheelType = document.getElementById("wheel-type").value;
        const oilType = document.getElementById("oil-type").value;
        const modelId = getData("model_id");

        const postData = {
            model_name: modelName,
            model_version: modelVersion,
            model_type_name: modelTypeName,
            fuel_type: fuelType,
            coolant_type: coolantType,
            suspension_type: suspensionType,
            wheel_type: wheelType,
            oil_type: oilType,
            model_id: modelId
        };

        fetch("handle_update_model.php", {
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