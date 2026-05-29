<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/intervention.php";

$data = parseInput();

$intervention_id = getValue($data, 'intervention_id');
$intervention_name = getValue($data, 'intervention_name');

if (!interventionExists($intervention_id)) {
    die("L'intervention renseignée n'existe pas.");
}

$request = "UPDATE interventions SET intervention_name = $1 WHERE intervention_id = $2;";
query($request, array($intervention_name, $intervention_id));

foreach ($data['model_ids'] as $modelData) {
    if ($modelData[1] == '') {
        $request = "DELETE FROM models_interventions_prices WHERE model_id = $1 AND intervention_id = $2;";
        query($request, array($modelData[0], $intervention_id));
        continue;
    }
    $request = "INSERT INTO models_interventions_prices (model_id, intervention_id, estimated_price)
                VALUES ($1, $2, $3)
                ON CONFLICT (model_id, intervention_id) DO UPDATE SET estimated_price = $3;";
    query($request, array($modelData[0], $intervention_id, $modelData[1]));
}

foreach ($data['model_type_ids'] as $modelData) {
    if ($modelData[1] == '') {
        $request = "DELETE FROM model_types_interventions_prices WHERE model_type_id = $1 AND intervention_id = $2;";
        query($request, array($modelData[0], $intervention_id));
        continue;
    }
    $request = "INSERT INTO model_types_interventions_prices (model_type_id, intervention_id, estimated_price)
                VALUES ($1, $2, $3)
                ON CONFLICT (model_type_id, intervention_id) DO UPDATE SET estimated_price = $3;";
    query($request, array($modelData[0], $intervention_id, $modelData[1]));
}


?>