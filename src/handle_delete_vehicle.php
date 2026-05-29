<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/vehicle.php";

$data = parseInput();

$vehicle_id = getValue($data, 'vehicle_id');

if (!vehicleExists($vehicle_id)) {
	die("Le véhicule renseigné n'existe pas.");
}

$request = "CALL delete_vehicle ($1)";
query($request, array($vehicle_id));

?>