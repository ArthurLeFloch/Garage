<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/model_type.php";

$data = parseInput();

$modelTypeId = getValue($data, 'model_type_id');
$modelTypeName = getValue($data, 'model_type_name');

if (!modelTypeExists($modelTypeId)) {
    die("Le type de modèle renseigné n'existe pas.");
}

$request = "UPDATE model_types SET model_type_name = $2
            WHERE model_type_id = $1;";

try {
    query($request, array($modelTypeId, $modelTypeName));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
