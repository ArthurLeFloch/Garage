<?php
include "../utils/query.php";
include "../fragments/table.php";
include "../fragments/utils.php";

cardHeader("Statistiques");

// STAT 1 : Liste des clients avec le nombre de véhicules qui ont été entretenus
// lors de l’année courante

$request = "SELECT * FROM models_given_in_maintenance_current_year()";
$res = query($request);

$table = new Table("stat-client-count-maitenance", "Liste des modèles de véhicule pris en charge lors de l’année écoulée");
$table->show($res);

// STAT 2 : Liste des modèles, avec le type d’intervention majoritaire pratiqué
// sur ces modèles

$request = "SELECT * FROM most_frequent_interventions_per_model()";
$res = query($request);

$table = new Table("stat-model-most-intervention", "Type d'interventions majoritaire pratiqués sur les modèles");
$table->show($res);

// STAT 3 : Liste des types de modèles, avec le type d’intervention majoritaire pratiqué
// sur ces types de modèles

$request = "SELECT * FROM most_frequent_interventions_per_model_type()";
$res = query($request);

$table = new Table("stat-model-type-most-intervention", "Type d'interventions majoritaire pratiqués sur les types de modèles");
$table->show($res);

// STAT 4 : Le nombre d’heures facturées des derniers mois

$request = "SELECT * FROM maintenance_hours_per_month()";
$res = query($request);

$table = new Table("stat-maintenance-hours-per-month", "Nombre d'heures facturées des derniers mois");
$table->show($res);

// STAT 5 : Le montant total d’argent dépensé en maintenances à ce garage par client

$request = "SELECT client_name, CONCAT(COALESCE(total_amount_spent_in_maintenance, 0), '€') AS total_amount_spent_in_maintenance FROM clients_total_amount_spent_in_maintenance();";
$res = query($request);

$table = new Table("stat-clients-total-amount-spent-in-maintenance", "Montant total d'argent dépensé en maintenances à ce garage par client");
$table->show($res);

cardFooter();
?>