import { menuBack, getData } from "../menu.js";
import { createToast } from "../utils/toast.js";

export function load() {
    document.getElementById("submitUpdateClient").addEventListener("click", function (event) {
        event.preventDefault();

        const id = getData("client_id");
        const surname = document.getElementById("surname").value;
        const name = document.getElementById("name").value;
        const address = document.getElementById("address").value;
        const mobile = document.getElementById("mobile").value;
        const email = document.getElementById("email").value;

        const postData = {
            id: id,
            surname: surname,
            name: name,
            address: address,
            mobile: mobile,
            email: email
        };

        fetch("handle_update_client.php", {
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
