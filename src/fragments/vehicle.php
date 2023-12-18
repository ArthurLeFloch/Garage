<?php

require_once('../utils/parse.php');
require_once('../utils/query.php');
require_once('../fragments/card.php');

function vehicleFragment($vehicle_id)
{
    $request = "SELECT vin_number, plate_number, registration_date, model_name, model_version, part_name, model_type_name, manufacturer_name AS Carburant FROM vehicles
                INNER JOIN models USING (model_id)
                INNER JOIN model_types USING (model_type_id)
                INNER JOIN parts ON (part_id = fuel_id)
                INNER JOIN manufacturers USING (manufacturer_id)
                WHERE vehicle_id = $1;";

    $res = query($request, array($vehicle_id));
    $array = pg_fetch_array($res);
    $vin_number = htmlspecialchars($array[0]);
    $plate_number = htmlspecialchars($array[1]);
    $registration_date = htmlspecialchars($array[2]);
    $model_name = htmlspecialchars($array[3]);
    $model_version = htmlspecialchars($array[4]);
    $fuel = htmlspecialchars($array[5]);
    $model_type_name = htmlspecialchars($array[6]);
    $manufacturer_name = htmlspecialchars($array[7]);

    cardHeader("Véhicule : $manufacturer_name $model_name ($fuel)", false);

    echo "<p class='card-text'><b>Type</b> : $model_type_name</p>";
    echo "<p class='card-text'><b>Version</b> : $model_version</p>";

    echo "<p class='card-text'><b>Immatriculation</b> : $plate_number</p>";
    echo "<p class='card-text'><b>Numéro de série</b> : $vin_number</p>";
    echo "<p class='card-text'><b>Date d'immatriculation</b> : $registration_date</p>";

    cardFooter();
}
