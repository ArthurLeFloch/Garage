<?php
include "./utils/parse.php";
include "./utils/query.php";

$data = parseInput();

$manufacturer_name = getValue($data, 'manufacturer_name');

$request = "CALL insert_manufacturer ($1)";

try {
    query($request, array($manufacturer_name));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout du modèle.");
}

?>
