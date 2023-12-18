import { getData, menuBack } from "../menu.js";
import { createToast } from "../utils/toast.js";

function getEmployeesSessions() {
    const employeesSessions = [];
    const container = document.getElementById("employees_sessions_container");

    container.querySelectorAll("[id^=employee_sessions-]").forEach((employeeSessions) => {
        const employeeId = employeeSessions.id.split("-")[1];
        const sessions = [];

        employeeSessions.querySelectorAll("tr").forEach((row) => {
            const work_date = row.querySelector("#work_date").value;
            const start_time = row.querySelector("#start_time").value;
            const end_time = row.querySelector("#end_time").value;

            if (work_date.length === 0 || start_time.length === 0 || end_time.length === 0) {
                return;
            }

            sessions.push({
                work_date: row.querySelector("#work_date").value,
                start_time: row.querySelector("#start_time").value,
                end_time: row.querySelector("#end_time").value
            });
        });

        employeesSessions.push({
            employee_id: employeeId,
            sessions: sessions
        });
    });

    return employeesSessions;
}

function addEmployeeSession(employee_id) {
    const employeeSessions = document.getElementById("employee_sessions-" + employee_id);

    const row = document.createElement("tr");

    const work_date_td = document.createElement("td");
    const work_date = document.createElement("input");
    work_date.type = "date";
    work_date.id = "work_date";
    work_date.classList.add("form-control");
    work_date_td.appendChild(work_date);
    row.appendChild(work_date_td);

    const start_time_td = document.createElement("td");
    const start_time = document.createElement("input");
    start_time.type = "time";
    start_time.id = "start_time";
    start_time.classList.add("form-control");
    start_time_td.appendChild(start_time);
    row.appendChild(start_time_td);

    const end_time_td = document.createElement("td");
    const end_time = document.createElement("input");
    end_time.type = "time";
    end_time.id = "end_time";
    end_time.classList.add("form-control");
    end_time_td.appendChild(end_time);
    row.appendChild(end_time_td);

    const remove_td = document.createElement("td");
    const remove = document.createElement("button");
    remove.type = "button";
    remove.classList.add("btn", "btn-danger");
    remove.textContent = "Supprimer";
    remove.addEventListener("click", function () {
        row.remove();
    });
    remove_td.appendChild(remove);
    row.appendChild(remove_td);

    employeeSessions.appendChild(row);
}

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

function updateTotalPrice() {
    const maintenanceId = getData("maintenance_id");
    const interventionIds = getCheckedInterventions();

    const postData = {
        maintenance_id: maintenanceId,
        intervention_ids: interventionIds
    };

    fetch("handle_get_maintenance_price.php", {
        method: "POST",
        body: JSON.stringify(postData),
        headers: { "Content-Type": "application/json" }
    })
        .then(response => response.text())
        .then(data => {
            document.getElementById("total_price").value = data;
        })
        .catch(error => console.error("Error fetching content:", error));
}

export function load() {
    document.querySelectorAll("[id^=add_session-]").forEach((employeeSessions) => {
        const employeeId = employeeSessions.id.split("-")[1];
        addEmployeeSession(employeeId);
        employeeSessions.addEventListener("click", function () {
            addEmployeeSession(employeeId);
        });
    });

    document.querySelectorAll("[id^=delete_session-]").forEach((employeeSessions) => {
        employeeSessions.addEventListener("click", function () {
            employeeSessions.parentElement.parentElement.remove();
        });
    });

    document.getElementById("updateMaintenance").addEventListener("click", function (event) {
        event.preventDefault();

        const maintenanceId = getData("maintenance_id");
        const plannerId = document.getElementById("planner").value;
        const startDate = document.getElementById("start_date").value;
        const duration = document.getElementById("duration").value;
        const totalPrice = document.getElementById("total_price").value;
        const mileage = document.getElementById("mileage").value;
        const notes = document.getElementById("maintenance_note").value;
        const status = document.getElementById("status").value;
        const interventionIds = getCheckedInterventions();
        const employeesSessions = getEmployeesSessions();

        const postData = {
            maintenance_id: maintenanceId,
            planner_id: plannerId,
            start_date: startDate,
            duration: duration,
            total_price: totalPrice,
            mileage: mileage,
            intervention_ids: interventionIds,
            status: status,
            employees_sessions: employeesSessions,
            notes: notes
        };

        fetch("handle_update_maintenance.php", {
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

    document.getElementById("checkbox_container").querySelectorAll("input[type=checkbox]").forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            updateTotalPrice();
        });
    });
}