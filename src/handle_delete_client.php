<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/client.php";

$data = parseInput();

$client_id = getValue($data, 'client_id');

if (!clientExists($client_id)) {
	die("Le client renseigné n'existe pas.");
}

$request = "CALL delete_client ($1)";
query($request, array($client_id));

?>