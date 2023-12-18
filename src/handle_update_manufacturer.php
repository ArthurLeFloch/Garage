<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/manufacturer.php";

$data = parseInput();

$manufacturerId = getValue($data, 'manufacturer_id');
$manufacturerName = getValue($data, 'manufacturer_name');

if (!manufacturerExists($manufacturerId)) {
    die("Le fabricant renseigné n'existe pas.");
}

$request = "UPDATE manufacturers SET manufacturer_name = $2
            WHERE manufacturer_id = $1;";

try {
    query($request, array($manufacturerId, $manufacturerName));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
