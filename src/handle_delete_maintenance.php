<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/maintenance.php";

$data = parseInput();

$maintenance_id = getValue($data, 'maintenance_id');

if (!maintenanceExists($maintenance_id)) {
	die("La maintenance renseignée n'existe pas.");
}

$request = "DELETE FROM maintenances WHERE maintenance_id = $1;";
query($request, array($maintenance_id));

?>