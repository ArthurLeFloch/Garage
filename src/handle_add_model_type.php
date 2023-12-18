<?php
include "./utils/parse.php";
include "./utils/query.php";

$data = parseInput();

$model_type_name = getValue($data, 'model_type_name');

$request = "CALL insert_model_type ($1)";

try {
    query($request, array($model_type_name));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
