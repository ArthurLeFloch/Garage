import { menuBack, getData } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    document.getElementById("addPrescription").addEventListener("click", function (event) {
        event.preventDefault();

        const dateOfTheDay = new Date();

        const intervention = document.getElementById("intervention").value;
        const to_do_before_date = document.getElementById("to_do_before_date").value;
        const planner = document.getElementById("planner").value;
        const date = dateOfTheDay.getFullYear() + "-" + dateOfTheDay.getMonth() + "-" + dateOfTheDay.getDate();

        const postData = {
            intervention_id: intervention,
            to_do_before_date: to_do_before_date,
            planner: planner,
            date: date,
            vehicle_id: getData('vehicle_id')
        };

        fetch("handle_add_prescription.php", {
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