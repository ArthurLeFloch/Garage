import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

function getCheckedInterventions() {
    const interventionIds = [];
    document.querySelectorAll("input[type=checkbox]").forEach((checkbox) => {
        if (checkbox.checked) {
            interventionIds.push(checkbox.value);
        }
    }
    );
    return interventionIds;
}

export function load() {
    document.getElementById("addExternMaintenance").addEventListener("click", function (event) {
        event.preventDefault();

        const vehicleId = getData("vehicle_id");
        const garageName = document.getElementById("garage").value;
        const startDate = document.getElementById("start_date").value;
        const endDate = document.getElementById("end_date").value;
        const interventionIds = getCheckedInterventions();

        const postData = {
            vehicle_id: vehicleId,
            garage_name: garageName,
            start_date: startDate,
            end_date: endDate,
            intervention_ids: interventionIds
        };

        fetch("handle_add_extern_maintenance.php", {
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