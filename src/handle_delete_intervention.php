<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/intervention.php";

$data = parseInput();

$intervention_id = getValue($data, 'intervention_id');

if (!interventionExists($intervention_id)) {
	die("L'intervention renseignée n'existe pas.");
}

$request = "DELETE FROM interventions WHERE intervention_id = $1;";
query($request, array($intervention_id));

?>