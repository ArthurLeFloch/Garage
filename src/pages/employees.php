<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
require_once "../utils/field_names.php";

loadJS('./pages/employees.js');

cardHeader("Employés");

$request = "SELECT employee_id, employee_first_name, employee_last_name, employee_address, employee_email, employee_mobile
            FROM employees
            ORDER BY employee_id;";
$res = query($request);

$table = new Table("employee-list");

$table->set_hidden_fields(array(0));

$table->add_button("employee-details", "Détails", array(0));
$table->add_button("employee-update", "Modifier", array(0));
$table->add_button("employee-delete", "Supprimer", array(0), false);

$table->show($res);

?>

<a href="#" class="btn btn-primary" id="newEmployee">
    Ajouter un employé
</a>

<?php
cardFooter();
?>