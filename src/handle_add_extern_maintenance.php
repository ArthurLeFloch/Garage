<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/vehicle.php";
include "./db-utils/extern_garage.php";
include "./db-utils/intervention.php";

$data = parseInput();

$vehicle_id = getValue($data, 'vehicle_id');
$garage_name = getValue($data, 'garage_name');
$start_date = getValue($data, 'start_date');
$end_date = getValue($data, 'end_date');
$intervention_ids = getValue($data, 'intervention_ids');

if (!isDate($start_date)) {
    die("La date de début n'est pas valide.");
}
if (!isDate($end_date)) {
    die("La date de fin n'est pas valide.");
}
if (!isDateBefore($start_date, $end_date)) {
    die("La date de début doit être avant la date de fin.");
}

if (!vehicleExists($vehicle_id)) {
    die("Le véhicule renseigné n'existe pas.");
}

$id = null;
if (!externGarageNameExists($garage_name)) {
    $request = "INSERT INTO extern_garages (extern_garage_name) VALUES ($1) RETURNING extern_garage_id;";
    $res = query($request, array($garage_name));
    $id = pg_fetch_array($res)[0];
} else {
    $request = "SELECT extern_garage_id FROM extern_garages WHERE extern_garage_name = $1;";
    $res = query($request, array($garage_name));
    $id = pg_fetch_array($res)[0];
}

foreach ($intervention_ids as $intervention_id) {
    if (!is_numeric($intervention_id) || !interventionExists($intervention_id)) {
        die("L'intervention renseignée n'existe pas.");
    }
}

$request = "SELECT extern_garage_id FROM extern_garages WHERE extern_garage_name = $1;";
$res = query($request, array($garage_name));

$id = pg_fetch_array($res)[0];


$request = "INSERT INTO extern_maintenances (vehicle_id, extern_garage_id, extern_start_date, extern_end_date)
            VALUES ($1, $2, $3, $4)
            RETURNING extern_maintenance_id";

$res = query($request, array($vehicle_id, $id, $start_date, $end_date));
$extern_maintenance_id = pg_fetch_array($res)[0];


foreach ($intervention_ids as $intervention_id) {
    $request = "INSERT INTO extern_maintenances_interventions (extern_maintenance_id, intervention_id)
            VALUES ($1, $2);";

    query($request, array($extern_maintenance_id, $intervention_id));
}
