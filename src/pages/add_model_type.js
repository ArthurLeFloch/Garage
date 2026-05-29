import { menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    document.getElementById("addModelType").addEventListener("click", function (event) {
        event.preventDefault();

        const model_type_name = document.getElementById("model_type_name").value;


        const postData = {
            model_type_name: model_type_name
        };

        fetch("handle_add_model_type.php", {
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