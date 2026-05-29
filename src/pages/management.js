import { menuSetChild } from "../menu.js";
import { stats, manageEmployees, manageModelTypes, manageManufacturers, managePartTypes, interventionsMenu } from "../logic.js";

export function load() {
    document.getElementById("loadStats").addEventListener("click", function () {
        menuSetChild(stats);
    });

    document.getElementById("loadEmployees").addEventListener("click", function () {
        menuSetChild(manageEmployees);
    });

    document.getElementById("loadModelTypes").addEventListener("click", function () {
        menuSetChild(manageModelTypes);
    });

    document.getElementById("loadManufacturers").addEventListener("click", function () {
        menuSetChild(manageManufacturers);
    });

    document.getElementById("loadParts").addEventListener("click", function () {
        menuSetChild(managePartTypes);
    });

    document.getElementById("loadInterventions").addEventListener("click", function () {
        menuSetChild(interventionsMenu);
    });
}