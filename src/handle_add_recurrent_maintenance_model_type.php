<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/model_type.php";
include "./db-utils/intervention.php";

$data = parseInput();

$model_type = getValue($data, 'model_type');
$interventions = getValue($data, 'interventions');
$mileage = getValue($data, 'mileage');
$days = getValue($data, 'days');

if (!modelTypeExists($model_type)) {
    die("Le type de modèle renseigné n'existe pas.");
}

foreach ($interventions as $intervention)
{
    if (!interventionExists($intervention)) {
        die("L'intervention renseignée n'existe pas.");
    }    
}

try {
    if ($days == 0) {
        if ($mileage == 0) {
            die("Il faut spécifier un kilométrage ou un nombre de jours");
        } else { // mileage
            $request = "SELECT insert_recurrent_maintenance_model_type_by_mileage ($1, $2, $3);";
            $result = query($request, array($mileage, $model_type, $interventions[0]));
        }
    } else if ($mileage == 0) { // days
        $request = "SELECT insert_recurrent_maintenance_model_type_by_days ($1, $2, $3);";
        $result = query($request, array($days, $model_type,  $interventions[0]));
    } else { // both
        die("Il n'est pas possible de définir une maintenance récurrente à la fois pour un kilométrage et un nombre de jour");
    }
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout de la maintenance récurrente.");
}

if (!$result) {
    die("Une erreur est survenue lors de l'ajout de la maintenance. Pas de retour de la base de données.");
}

$maintenance = pg_fetch_array($result)[0];

foreach (array_slice($interventions, 1) as $intervention) {
    $request = "CALL insert_recurrent_maintenance_intervention ($1, $2);";

    query($request, array($maintenance, $intervention));
}

?>
