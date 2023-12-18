<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
require_once "../utils/field_names.php";

loadJS('./pages/model_types.js');

cardHeader("Types de modèles");

$request = "SELECT model_type_id, model_type_name
            FROM model_types
            ORDER BY model_type_name;";
$res = query($request);

$table = new Table("model-type-list");

$table->set_hidden_fields(array(0));

$table->add_button("model-type-update", "Modifier", array(0));
$table->add_button("model-type-delete", "Supprimer", array(0), false);

$table->show($res);

?>

<a href="#" class="btn btn-primary" id="newModelType">
    Ajouter un type de modèle
</a>

<?php
cardFooter();
?>