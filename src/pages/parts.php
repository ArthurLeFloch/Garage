<?php

include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";
include "../fragments/table.php";

loadJS('./pages/parts.js');

$part_type_id = parseGet("part_type_id");

$request = "SELECT part_type_name FROM part_types WHERE part_type_id = $1;";
$res = query($request, array($part_type_id));
$array = pg_fetch_array($res);
$part_type_name = $array[0];

cardHeader("Liste des pièces - " . $part_type_name);


$request = "SELECT part_id, part_name, part_reference, CONCAT(COALESCE(unitary_price, 0), '€') AS unitary_price FROM parts
            WHERE part_type_id = $1
            ORDER BY part_name;";
$res = query($request, array($part_type_id));

$table = new Table("part-list");
$table->set_hidden_fields(array(0));
$table->add_button("part-update", "Modifier", array(0));
$table->add_button("part-delete", "Supprimer", array(0), false);
$table->show($res);
?>

<a href="#" class="btn btn-primary" id="newPart">
    Ajouter une pièce
</a>

<?php
cardFooter();
?>
