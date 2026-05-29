<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/part_type.php";

$data = parseInput();

$partTypeId = getValue($data, 'part_type_id');
$partName = getValue($data, 'part_name');
$partReference = getValue($data, 'part_reference', false);
$unitaryPrice = getValue($data, 'unitary_price');

if (!partTypeExists($partTypeId)) {
    die("Le type de pièce renseigné n'existe pas.");
}

if (!is_numeric($unitaryPrice)) {
    die("Le prix est invalide.");
}

$request = "CALL insert_part ($1, $2, $3, $4)";
try {
    query($request, array($partTypeId, $partName, $partReference, $unitaryPrice));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
