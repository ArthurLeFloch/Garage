import {menuSetChild, setData} from "../menu.js";
import {seeMaintenanceFromSearch} from "../logic.js";

// Extra safe script to throw understandable errors in case an error occurs in PHP
export function load() {
    const detailButtons = document.getElementById("maintenance-list");
    if (detailButtons === null) {
        console.error("Table not found!");
        return;
    }
    detailButtons.querySelectorAll("a").forEach((button) => {
        if (button.id.startsWith("maintenance-details-")) {
            button.addEventListener("click", function () {
                setData("maintenance_id", button.id.split("-")[2]);
                setData("vehicle_id", button.id.split("-")[3]);
                setData("client_id", button.id.split("-")[4]);
                menuSetChild(seeMaintenanceFromSearch);
            });
        }
    });
}
