import { menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

function getCheckedInterventions() {
    const interventionIds = [];
    document.getElementById("checkbox_container").querySelectorAll("input[type=checkbox]").forEach((checkbox) => {
        if (checkbox.checked) {
            interventionIds.push(checkbox.value);
        }
    }
    );
    return interventionIds;
}

export function load() {
    document.getElementById("addRecMaintenanceModelType").addEventListener("click", function (event) {
        event.preventDefault();

        const modelType = document.getElementById("model-type").value;
        const mileage = document.getElementById("mileage").value;
        const days = document.getElementById("days").value;

        const interventions = getCheckedInterventions();

        const postData = {
            model_type: modelType,
            interventions: interventions,
            mileage: mileage,
            days: days
        };

        fetch("handle_add_recurrent_maintenance_model_type.php", {
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