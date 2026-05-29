<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/employee.php";

$data = parseInput();

$id = getValue($data, 'id');
$surname = getValue($data, 'surname');
$name = getValue($data, 'name');
$address = getValue($data, 'address');
$mobile = getValue($data, 'mobile');
$email = getValue($data, 'email');

if (!employeeExists($id)) {
    die("L'employé renseigné n'existe pas.");
}

# Verify email format
if (!isEmail($email)) {
    die("Le format de l'adresse email est invalide.");
}
# Verify phone number format
if (!isPhoneNumber($mobile)) {
    die("Le format du numéro de téléphone est invalide.");
}

$request = "UPDATE employees SET employee_first_name = $2, employee_last_name = $3, employee_address = $4, employee_email = $5, employee_mobile = $6
			WHERE employee_id = $1";
query($request, array($id, $surname, $name, $address, $email, $mobile));

?>
