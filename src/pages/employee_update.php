<!-- Required GET parameters: employee_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/employee_update.js');

cardHeader("Modification des informations sur l'employé");

$employee_id = parseGet('employee_id');

$request = "SELECT employee_first_name, employee_last_name, employee_address, employee_email, employee_mobile FROM employees
            WHERE employee_id = $1";
$res = query($request, array($employee_id));
$array = pg_fetch_array($res);
$name = htmlspecialchars($array[0]);
$surname = htmlspecialchars($array[1]);
$address = htmlspecialchars($array[2]);
$email = htmlspecialchars($array[3]);
$mobile = htmlspecialchars($array[4]);

echo "
    <form class='row g-3'>
        <div class='col-6'>
            <label for='name' class='form-label'>Nom</label>
            <input type='text' class='form-control' id='name' value='" . $surname . "'>
        </div>
        <div class='col-6'>
            <label for='surname' class='form-label'>Prénom</label>
            <input type='text' class='form-control' id='surname' value='" . $name . "'>
        </div>
        <div class='col-12'>
            <label for='address' class='form-label'>Adresse</label>
            <input type='text' class='form-control' id='address' value='" . $address . "'>
        </div>
        <div class='col-6'>
            <label for='email' class='form-label'>Adresse mail</label>
            <input type='text' class='form-control' id='email' value='" . $email . "'>
        </div>
        <div class='col-6'>
            <label for='mobile' class='form-label'>Téléphone</label>
            <input type='text' class='form-control' id='mobile' value='" . $mobile . "'>
        </div>
        <div class='col-12'>
            <button type='submit' class='btn btn-primary' id='submitUpdateEmployee'>Valider</button>
        </div>
    </form>";

cardFooter();

?>
