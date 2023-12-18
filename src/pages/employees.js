import { menuSetChild, setData, menuReload } from "../menu.js";
import { employeeDetails, employeeUpdate, newEmployee } from "../logic.js";
import { createToast } from "../utils/toast.js";
import { createConfirmModal } from "../utils/modal.js";

function onDelete(employeeId) {
    const postData = {
        employee_id: employeeId
    };

    fetch("handle_delete_employee.php", {
        method: "POST",
        body: JSON.stringify(postData),
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.text())
        .then(data => {
            if (data.length === 0) {
                menuReload();
            } else {
                createToast("Erreur côté serveur", data);
            }
        })
        .catch(error => console.error("Error fetching content:", error));
}

export function load() {
    document.querySelectorAll('[id^=employee-details]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("employee_id", element.id.split("-")[2]);
            menuSetChild(employeeDetails);
        });
    });

    document.querySelectorAll('[id^=employee-update]').forEach((element) => {
        element.addEventListener('click', function () {
            setData("employee_id", element.id.split("-")[2]);
            menuSetChild(employeeUpdate);
        });
    });

    document.querySelectorAll('[id^=employee-delete]').forEach((element) => {
        element.addEventListener('click', function () {
            const employeeId = element.id.split("-")[2];

            createConfirmModal(
                "Supprimer un employé",
                "Êtes-vous sûr de vouloir supprimer cet employé ?",
                () => onDelete(employeeId)
            );
        });
    });

    document.getElementById("newEmployee").addEventListener("click", function () {
        menuSetChild(newEmployee);
    });
}