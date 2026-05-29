<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/prescription.php";

$data = parseInput();

$prescription_id = getValue($data, 'prescription_id');

if (!prescriptionExists($prescription_id)) {
	die("La prescription renseignée n'existe pas.");
}

$request = "DELETE FROM prescriptions WHERE prescription_id = $1;";
query($request, array($prescription_id));

?>