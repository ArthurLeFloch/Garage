<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/part.php";
include "./db-utils/model_type.php";
include "./db-utils/manufacturer.php";

$data = parseInput();

$modelName = getValue($data, 'model_name');
$modelVersion = getValue($data, 'model_version');
$manufacturerId = getValue($data, 'manufacturer_id');
$modelTypeName = getValue($data, 'model_type_name');
$fuelType = getValue($data, 'fuel_type');
$coolantType = getValue($data, 'coolant_type');
$suspensionType = getValue($data, 'suspension_type');
$wheelType = getValue($data, 'wheel_type');
$oilType = getValue($data, 'oil_type');

if (!manufacturerExists($manufacturerId)) {
    die("Le constructeur renseigné n'existe pas.");
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

$request = "CALL insert_model ($1, $2, $3, $4, $5, $6, $7, $8, $9)";

try {
    query($request, array($modelName, $modelVersion, $fuelType, $coolantType, $suspensionType, $wheelType, $oilType, $manufacturerId, $modelTypeName));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
