<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
require_once "../utils/field_names.php";

loadJS('./pages/part_types.js');

cardHeader("Types de pièces");

$request = "SELECT part_type_id, part_type_name 
            FROM part_types
            ORDER BY part_type_name;";
$res = query($request);

$table = new Table("part-list");

$table->set_hidden_fields(array(0));

$table->add_button("part-type-details", "Détails", array(0));
$table->add_button("part-type-update", "Modifier", array(0));
$table->add_button("part-type-delete", "Supprimer", array(0), false);

$table->show($res);

?>

<a href="#" class="btn btn-primary" id="newPartType">
    Ajouter un type de pièce
</a>

<?php
cardFooter();
?>