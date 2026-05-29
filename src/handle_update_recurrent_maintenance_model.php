<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/recurrent_maintenance.php";
include "./db-utils/intervention.php";
include "./db-utils/model.php";

$data = parseInput();

$maintenance = getValue($data, 'maintenance');
$model = getValue($data, 'model');
$interventions = getValue($data, 'interventions');
$mileage = getValue($data, 'mileage');
$days = getValue($data, 'days');

if (!recurrentMaintenanceExists($maintenance)) {
    die("La maintenance renseignée n'existe pas.");
}

if (!modelExists($model)) {
    die("Le modèle renseigné n'existe pas.");
}

try {
    if ($days == 0) {
        if ($mileage == 0) {
            die("Il faut spécifier un kilométrage ou un nombre de jours");
        } else { // mileage
            $request = "CALL update_recurrent_maintenance ($1, $2, NULL, $3);";
            query($request, array($maintenance, $mileage, $model));
        }
    } else if ($mileage == 0) { // days
        $request = "CALL update_recurrent_maintenance ($1, NULL, $2, $3);";
        query($request, array($maintenance, $days, $model));
    } else { // both
        die("Il n'est pas possible de définir une maintenance récurrente à la fois pour un kilométrage et un nombre de jour");
    }
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout de la maintenance récurrente.");
}

$request = "DELETE FROM recurrent_maintenances_interventions WHERE recurrent_maintenance_id = $1 AND intervention_id NOT IN (";
for ($i = 0; $i < count($interventions); $i++) {
    $request .= "$" . ($i + 2);
    if ($i != count($interventions) - 1) {
        $request .= ", ";
    }
}
$request .= ");";
query($request, array_merge(array($maintenance), $interventions));


$request = "INSERT INTO recurrent_maintenances_interventions (recurrent_maintenance_id, intervention_id)
            SELECT $1, intervention_id FROM interventions
            WHERE intervention_id IN (";
for ($i = 0; $i < count($interventions); $i++) {
    $request .= "$" . ($i + 2);
    if ($i != count($interventions) - 1) {
        $request .= ", ";
    }
}
$request .= ") AND intervention_id NOT IN (SELECT intervention_id FROM recurrent_maintenances_interventions WHERE recurrent_maintenance_id = $1);";
query($request, array_merge(array($maintenance), $interventions));
