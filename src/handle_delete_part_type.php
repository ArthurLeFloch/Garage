<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/part_type.php";

$data = parseInput();

$part_type_id = getValue($data, 'part_type_id');

if (!partTypeExists($part_type_id)) {
	die("Le type de pièce renseigné n'existe pas.");
}

$request = "DELETE FROM part_types WHERE part_type_id = $1";
query($request, array($part_type_id));

?>