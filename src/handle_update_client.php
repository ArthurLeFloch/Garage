<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/client.php";

$data = parseInput();

$id = getValue($data, 'id');
$surname = getValue($data, 'surname');
$name = getValue($data, 'name');
$address = getValue($data, 'address');
$mobile = getValue($data, 'mobile');
$email = getValue($data, 'email');

if (!clientExists($id)) {
    die("Le client renseigné n'existe pas.");
}

# Verify email format
if (!isEmail($email)) {
    die("Le format de l'adresse email est invalide.");
}
# Verify phone number format
if (!isPhoneNumber($mobile)) {
    die("Le format du numéro de téléphone est invalide.");
}

$request = "CALL update_client ($1, $2, $3, $4, $5, $6)";
query($request, array($id, $surname, $name, $address, $email, $mobile));

?>
