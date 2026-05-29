<?php

require_once('../utils/parse.php');
require_once('../utils/query.php');
require_once('../fragments/card.php');

function employeeFragment($employee_id)
{
    $request = "SELECT employee_first_name, employee_last_name, employee_address, employee_email, employee_mobile FROM employees
                WHERE employee_id = $1";
    $res = query($request, array($employee_id));
    $array = pg_fetch_array($res);
    $surname = htmlspecialchars($array[0]);
    $name = htmlspecialchars($array[1]);
    $address = htmlspecialchars($array[2]);
    $email = htmlspecialchars($array[3]);
    $mobile = htmlspecialchars($array[4]);

    cardHeader("Employé : $surname $name", false);

    echo "<p class='card-text'><b>Adresse</b> : $address</p>";
    echo "<p class='card-text'><b>Email</b> : $email</p>";
    echo "<p class='card-text'><b>Mobile</b> : $mobile</p>";

    cardFooter();
}
