<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/part_type.php";

$data = parseInput();

$partTypeId = getValue($data, 'part_type_id');
$partTypeName = getValue($data, 'part_type_name');

if (!partTypeExists($partTypeId)) {
    die("Le type de pièce renseigné n'existe pas.");
}

$request = "UPDATE part_types SET part_type_name = $2
            WHERE part_type_id = $1;";

try {
    query($request, array($partTypeId, $partTypeName));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
