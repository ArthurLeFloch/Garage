<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/part.php";

$data = parseInput();

$part_id = getValue($data, 'part_id');

if (!partExists($part_id)) {
	die("La pièce renseignée n'existe pas.");
}

$request = "DELETE FROM parts WHERE part_id = $1;";
query($request, array($part_id));

?>