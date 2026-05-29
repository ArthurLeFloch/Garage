<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/recurrent_maintenance.php";

$data = parseInput();

$maintenance_id = getValue($data, 'maintenance_id');

if (!recurrentMaintenanceExists($maintenance_id)) {
	die("La maintenance renseignée n'existe pas.");
}

$request = "DELETE FROM recurrent_maintenances WHERE recurrent_maintenance_id = $1";
query($request, array($maintenance_id));

?>