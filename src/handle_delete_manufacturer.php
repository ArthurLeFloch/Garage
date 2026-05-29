<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/manufacturer.php";

$data = parseInput();

$manufacturer_id = getValue($data, 'manufacturer_id');

if (!manufacturerExists($manufacturer_id)) {
	die("Le constructeur renseigné n'existe pas.");
}

$request = "DELETE FROM manufacturers WHERE manufacturer_id = $1";
query($request, array($manufacturer_id));

?>