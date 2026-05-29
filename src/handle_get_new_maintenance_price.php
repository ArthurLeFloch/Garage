<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/vehicle.php";
include "./db-utils/intervention.php";

$data = parseInput();

$vehicle_id = getValue($data, 'vehicle_id');
$intervention_ids = getValue($data, 'intervention_ids');

if (!vehicleExists($vehicle_id)) {
    die("Le véhicule renseigné n'existe pas.");
}

foreach ($intervention_ids as $intervention_id) {
    if (!interventionExists($intervention_id)) {
        die("L'intervention renseignée n'existe pas.");
    }
}

$total_price = 0;

foreach ($intervention_ids as $intervention_id) {
    $request = "SELECT models_interventions_prices.estimated_price FROM vehicles
                INNER JOIN models USING (model_id)
                INNER JOIN models_interventions_prices USING (model_id)
                WHERE vehicles.vehicle_id = $1 AND models_interventions_prices.intervention_id = $2
                LIMIT 1;";
    $res = query($request, array($vehicle_id, $intervention_id));
    $array = pg_fetch_array($res);
    if ($array == false) {
        continue;
    }
    $estimated_price = $array[0];
    if ($estimated_price != null) {
        $total_price += $estimated_price;
        continue;
    }

    $request = "SELECT model_types_interventions_prices.estimated_price FROM vehicles
                INNER JOIN models USING (model_id)
                INNER JOIN model_types USING (model_type_id)
                INNER JOIN model_types_interventions_prices USING (model_type_id)
                WHERE vehicles.vehicle_id = $1 AND model_types_interventions_prices.intervention_id = $2
                LIMIT 1;";
    $res = query($request, array($vehicle_id, $intervention_id));
    $array = pg_fetch_array($res);
    if ($array == false) {
        continue;
    }
    $estimated_price = $array[0];
    if ($estimated_price != null) {
        $total_price += $estimated_price;
    }
}

echo $total_price;