import { menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    const interventionInput = document.getElementById("intervention_name");
    const modelFields = document.getElementById("m-list").querySelectorAll("input");
    const modelTypeFields = document.getElementById("mtype-list").querySelectorAll("input");

    document.getElementById("addIntervention").addEventListener("click", function () {
        event.preventDefault();

        const interventionName = interventionInput.value;
        const modelIds = [];
        const modelTypeIds = [];

        modelFields.forEach((field) => modelIds.push([field.id.split("-")[1], field.value]));
        modelTypeFields.forEach((field) => modelTypeIds.push([field.id.split("-")[1], field.value]));

        const postData = {
            intervention_name: interventionName,
            model_ids: modelIds,
            model_type_ids: modelTypeIds
        };

        fetch("handle_add_intervention.php", {
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