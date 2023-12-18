<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/vehicle.php";
include "./db-utils/employee.php";
include "./db-utils/intervention.php";

$data = parseInput();

$vehicle = getValue($data, 'vehicle_id');
$planner = getValue($data, 'planner');
$date = getValue($data, 'date');
$to_do_before_date = getValue($data, 'to_do_before_date');
$intervention = getValue($data, 'intervention_id');

if (!vehicleExists($vehicle)) {
    die("Le véhicule renseigné n'existe pas.");
}

if (!employeeExists($planner)) {
    die("Le planificateur renseigné n'existe pas.");
}

if (!interventionExists($intervention)) {
    die("L'intervention renseignée n'existe pas.");
}

try {
    $request = "SELECT insert_prescription ($1, $2, $3, $4, $5)";
    query($request, array($vehicle, $planner, $date, $to_do_before_date, $intervention));
} catch (Exception $e) {
    die("Une erreur inconnue est survenue lors de l'ajout de la prescription.");
}


?>