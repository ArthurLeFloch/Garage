<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/model_type.php";

$data = parseInput();

$model_type_id = getValue($data, 'model_type_id');

if (!modelTypeExists($model_type_id)) {
	die("Le type de modèle renseigné n'existe pas.");
}

$request = "DELETE FROM model_types WHERE model_type_id = $1";
query($request, array($model_type_id));

?>