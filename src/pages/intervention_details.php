<!-- Required GET parameters: intervention_id -->

<?php
include "../utils/query.php";
include "../utils/parse.php";
include "../utils/hydration.php";
include "../fragments/utils.php";
include "../fragments/table.php";

cardHeader("Détails du type d'intervention");

echo "<p>Grille tarifaire :</p>";

$intervention_id = parseGet('intervention_id');

$request = "SELECT intervention_name FROM interventions
            WHERE intervention_id = $1;";

$res = query($request, array($intervention_id));

$name = pg_fetch_array($res)[0];

echo "<p>Nom d'intervention : " . htmlspecialchars($name) . "</p>";

echo "<div class='alert alert-primary' role='alert'>
        Si le modèle est renseigné, <b>les données sur un modèle précis prévalent</b> sur les données du type de modèle.
    </div>";

$request = "SELECT manufacturer_name, model_name, model_version, part_name AS \"fuel_name\", CONCAT(COALESCE(estimated_price, 0), '€') AS estimated_price FROM models_interventions_prices
            INNER JOIN interventions USING (intervention_id)
            INNER JOIN models USING (model_id)
            INNER JOIN manufacturers USING (manufacturer_id)
            INNER JOIN model_types USING (model_type_id)
            JOIN parts ON (fuel_id = parts.part_id)
            WHERE intervention_id = $1
            ORDER BY manufacturer_name, model_name, model_version;";
$res = query($request, array($intervention_id));

echo "<p>Grille des tarifs par modèle :</p>";

$table = new Table("model-list");

$table->show($res);


$request = "SELECT model_type_name, CONCAT(COALESCE(estimated_price, 0), '€') AS estimated_price FROM model_types_interventions_prices
            INNER JOIN interventions USING (intervention_id)
            INNER JOIN model_types USING (model_type_id)
            WHERE intervention_id = $1
            ;";
$res = query($request, array($intervention_id));

echo "<p>Grille des tarifs par types de modèle :</p>";

$table = new Table("mtype-list");

$table->show($res);

cardFooter();
?>