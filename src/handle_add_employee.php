<?php
include "./utils/parse.php";
include "./utils/query.php";

$data = parseInput();

$surname = getValue($data, 'surname');
$name = getValue($data, 'name');
$address = getValue($data, 'address');
$mobile = getValue($data, 'mobile');
$email = getValue($data, 'email');

if (!isEmail($email)) {
    die("L'email renseigné n'est pas valide.");
}
if (!isPhoneNumber($mobile)) {
    die("Le format du numéro de téléphone est invalide.");
}

$request = "CALL insert_employee ($1, $2, $3, $4, $5)";

query($request, array($surname, $name, $address, $email, $mobile));

?>
