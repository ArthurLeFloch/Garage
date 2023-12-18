<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/model.php";

$data = parseInput();

$model_id = getValue($data, 'model_id');

if (!modelExists($model_id)) {
	die("Le modèle renseigné n'existe pas.");
}

$request = "DELETE FROM models WHERE model_id = $1";
query($request, array($model_id));

?>