<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/extern_maintenance.php";

$data = parseInput();

$extern_maintenance_id = getValue($data, 'extern_maintenance_id');

if (!externMaintenanceExists($extern_maintenance_id)) {
	die("La maintenance externe renseignée n'existe pas.");
}

$request = "DELETE FROM extern_maintenances WHERE extern_maintenance_id = $1;";
query($request, array($extern_maintenance_id));

?>