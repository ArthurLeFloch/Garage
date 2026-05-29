<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/client.php";
include "./db-utils/model.php";

$data = parseInput();

$vin = getValue($data, 'vin');
$plate_number = getValue($data, 'plate_number');
$circulation_date = getValue($data, 'circulation_date');
$model_id = getValue($data, 'model_id');
$client_id = getValue($data, 'client_id');


if (!clientExists($client_id)) {
    die("Le client renseigné n'existe pas.");
}

if (!modelExists($model_id)) {
    die("Le modèle renseigné n'existe pas.");
}


$request = "CALL insert_vehicle ($1, $2, $3, $4, $5)";
query($request, array($vin, $plate_number, $circulation_date, $client_id, $model_id));

?>