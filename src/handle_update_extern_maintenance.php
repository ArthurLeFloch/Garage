<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/extern_maintenance.php";

$data = parseInput();

$extern_maintenance_id = getValue($data, 'extern_maintenance_id');
$start_date = getValue($data, 'start_date');
$end_date = getValue($data, 'end_date');
$intervention_ids = getValue($data, 'intervention_ids');

if (!externMaintenanceExists($extern_maintenance_id)) {
    die("La maintenance externe renseignée n'existe pas.");
}

if (!isDate($start_date)) {
    die("La date de début n'est pas valide.");
}
if (!isDate($end_date)) {
    die("La date de fin n'est pas valide.");
}
if (!isDateBefore($start_date, $end_date)) {
    die("La date de début doit être avant la date de fin.");
}

$request = "UPDATE extern_maintenances SET extern_start_date = $2, extern_end_date = $3
            WHERE extern_maintenance_id = $1;";
query($request, array($extern_maintenance_id, $start_date, $end_date));


$request = "DELETE FROM extern_maintenances_interventions WHERE extern_maintenance_id = $1 AND intervention_id NOT IN (";
for ($i = 0; $i < count($intervention_ids); $i++) {
    $request .= "$" . ($i + 2);
    if ($i != count($intervention_ids) - 1) {
        $request .= ", ";
    }
}
$request .= ");";
query($request, array_merge(array($extern_maintenance_id), $intervention_ids));


$request = "INSERT INTO extern_maintenances_interventions (extern_maintenance_id, intervention_id)
            SELECT $1, intervention_id FROM interventions
            WHERE intervention_id IN (";
for ($i = 0; $i < count($intervention_ids); $i++) {
    $request .= "$" . ($i + 2);
    if ($i != count($intervention_ids) - 1) {
        $request .= ", ";
    }
}
$request .= ") AND intervention_id NOT IN (SELECT intervention_id FROM extern_maintenances_interventions WHERE extern_maintenance_id = $1);";
query($request, array_merge(array($extern_maintenance_id), $intervention_ids));
