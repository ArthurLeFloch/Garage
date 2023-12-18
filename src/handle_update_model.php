<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/model.php";
include "./db-utils/part.php";
include "./db-utils/model_type.php";

$data = parseInput();

$modelId = getValue($data, 'model_id');
$modelName = getValue($data, 'model_name');
$modelVersion = getValue($data, 'model_version');
$modelTypeName = getValue($data, 'model_type_name');
$fuelType = getValue($data, 'fuel_type');
$coolantType = getValue($data, 'coolant_type');
$suspensionType = getValue($data, 'suspension_type');
$wheelType = getValue($data, 'wheel_type');
$oilType = getValue($data, 'oil_type');

if (!modelExists($modelId)) {
    die("Le modèle renseigné n'existe pas.");
}

if (!modelTypeExists($modelTypeName)) {
    die("Le type de modèle renseigné n'existe pas.");
}

if (!partExists($fuelType)) {
    die("Le type de carburant renseigné n'existe pas.");
}

if (!partExists($coolantType)) {
    die("Le type de liquide de refroidissement renseigné n'existe pas.");
}

if (!partExists($suspensionType)) {
    die("Le type de suspension renseigné n'existe pas.");
}

if (!partExists($wheelType)) {
    die("Le type de roue renseigné n'existe pas.");
}

if (!partExists($oilType)) {
    die("Le type d'huile renseigné n'existe pas.");
}

$request = "UPDATE models SET
                model_name = $2,
                model_version = $3,
                coolant_id = $4,
                suspension_id = $5,
                wheel_id = $6,
                oil_id = $7,
                fuel_id = $8,
                model_type_id = $9
            WHERE model_id = $1;";

try {
    query($request, array($modelId, $modelName, $modelVersion, $coolantType, $suspensionType, $wheelType, $oilType, $fuelType, $modelTypeName));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
