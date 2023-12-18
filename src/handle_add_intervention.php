<?php
include "./utils/parse.php";
include "./utils/query.php";

$data = parseInput();

$name = getValue($data, 'intervention_name');

$request = "INSERT INTO interventions (intervention_name) VALUES ($1) RETURNING intervention_id;";
$res = query($request, array($name));

$intervention_id = pg_fetch_array($res)[0];

foreach ($data['model_ids'] as $modelData) {
    if ($modelData[1] == "" || $modelData[0] == "" || !is_numeric($modelData[0]) || !is_numeric($modelData[1])) {
        continue;
    }
    $request = "INSERT INTO models_interventions_prices (model_id, intervention_id, estimated_price)
                VALUES ($1, $2, $3);";
    query($request, array($modelData[0], $intervention_id, $modelData[1]));
}

foreach ($data['model_type_ids'] as $modelData) {
    if ($modelData[1] == "" || $modelData[0] == "" || !is_numeric($modelData[0]) || !is_numeric($modelData[1])) {
        continue;
    }
    $request = "INSERT INTO model_types_interventions_prices (model_type_id, intervention_id, estimated_price)
                VALUES ($1, $2, $3);";
    query($request, array($modelData[0], $intervention_id, $modelData[1]));
}


?>