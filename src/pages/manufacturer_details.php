<!-- Required GET parameters: manufacturer_id -->

<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
require_once "../utils/field_names.php";

loadJS('./pages/manufacturer_details.js');

$manufacturer_id = parseGet('manufacturer_id');

$request = "SELECT manufacturer_name FROM manufacturers
            WHERE manufacturer_id = $1;";
$res = query($request, array($manufacturer_id));
$array = pg_fetch_array($res);
$manufacturer_name = $array[0];

cardHeader("Détails du constructeur " . $manufacturer_name);

$request = "SELECT  model_id, 
                    CONCAT(model_name, ' ', model_version) AS model_name,
                    coolant_id, 
                    suspension_id, 
                    wheel_id, 
                    oil_id, 
                    fuel_id
            FROM models
            JOIN manufacturers USING (manufacturer_id)
            WHERE manufacturer_id = $1
            ORDER BY model_name, coolant_id, suspension_id, wheel_id, oil_id, fuel_id;";
$res = query($request, array($manufacturer_id));

function get_part_name($part_id)
{
    $request = "SELECT part_name FROM parts
                WHERE part_id = $1;";
    $res = query($request, array($part_id));
    $array = pg_fetch_array($res);
    return $array[0];
}

$table = new Table("session-list");

$table->set_hidden_fields(array(0, 2, 3, 4, 5, 6));

$table->add_button("model-update", "Modifier", array(0));
$table->add_button("model-delete", "Supprimer", array(0), false);

$table->add_column("Carburant", "get_part_name", array(6));
$table->add_column("Liquide de refroidissement", "get_part_name", array(2));
$table->add_column("Suspension", "get_part_name", array(3));
$table->add_column("Roue", "get_part_name", array(4));
$table->add_column("Huile", "get_part_name", array(5));

$table->show($res);
?>

<a href="#" class="btn btn-primary" id="newModel">
    Ajouter un modèle
</a>

<?php
cardFooter();
?>

