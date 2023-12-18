<?php
include "./utils/parse.php";
include "./utils/query.php";

$data = parseInput();

$partTypeName = getValue($data, 'part_type_name');

$request = "SELECT insert_part_type ($1)";
try {
    query($request, array($partTypeName));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
