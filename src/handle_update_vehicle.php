<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/vehicle.php";
include "./db-utils/model.php";

$data = parseInput();

$vehicle_id = getValue($data, 'vehicle_id');
$vin = getValue($data, 'vin');
$plate_number = getValue($data, 'plate_number');
$circulation_date = getValue($data, 'circulation_date');
$model_id = getValue($data, 'model_id');

if (!vehicleExists($vehicle_id)) {
    die("Le véhicule renseigné n'existe pas.");
}

if (!modelExists($model_id)) {
    die("Le modèle renseigné n'existe pas.");
}

$request = "CALL update_vehicle ($1, $2, $3, $4, $5)";
query($request, array($vehicle_id, $vin, $plate_number, $circulation_date, $model_id));

?>