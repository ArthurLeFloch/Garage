<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/employee.php";

$data = parseInput();

$employee_id = getValue($data, 'employee_id');

if (!employeeExists($employee_id)) {
	die("L'employé renseigné n'existe pas.");
}

$request = "DELETE FROM employees WHERE employee_id = $1";
query($request, array($employee_id));

?>