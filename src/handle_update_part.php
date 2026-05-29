<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/part.php";
include "./db-utils/part_type.php";

$data = parseInput();

$partId = getValue($data, 'part_id');
$partTypeId = getValue($data, 'part_type_id');
$partName = getValue($data, 'part_name');
$partReference = getValue($data, 'part_reference', false);
$unitaryPrice = getValue($data, 'unitary_price');

if (!partExists($partId)) {
    die("La pièce renseignée n'existe pas.");
}

if (!partTypeExists($partTypeId)) {
    die("Le type de pièce renseigné n'existe pas.");
}

$request = "UPDATE parts SET
                part_type_id = $2,
                part_name = $3,
                part_reference = $4,
                unitary_price = $5
            WHERE part_id = $1;";

try {
    query($request, array($partId, $partTypeId, $partName, $partReference, $unitaryPrice));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
