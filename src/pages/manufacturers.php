<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
require_once "../utils/field_names.php";

loadJS('./pages/manufacturers.js');

cardHeader("Constructeurs");

$request = "SELECT manufacturer_id, manufacturer_name 
            FROM manufacturers
            ORDER BY manufacturer_name;";
$res = query($request);

$table = new Table("employee-list");

$table->set_hidden_fields(array(0));

$table->add_button("manufacturer-details", "Détails", array(0));
$table->add_button("manufacturer-update", "Modifier", array(0));
$table->add_button("manufacturer-delete", "Supprimer", array(0), false);

$table->show($res);

?>

<a href="#" class="btn btn-primary" id="newManufacturer">
    Ajouter un constructeur
</a>

<?php
cardFooter();
?>