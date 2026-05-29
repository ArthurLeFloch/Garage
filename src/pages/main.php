<?php
include "../utils/query.php";
include "../fragments/table.php";
include "../utils/hydration.php";
include "../fragments/utils.php";
include "../db-utils/maintenance.php";

loadJS('./pages/main.js');

cardHeader("Projet IT203 - Logiciel de gestion de garage");


$request = "SELECT maintenance_id, client_name, vehicle_name, plate_number FROM vehicles_under_maintenance_currently()";
$res = query($request);

$table = new Table("ongoing-maitenance", "Maintenance en cours");

$table->set_hidden_fields(array(0));

$table->add_button("maintenance-details", "Détails", array(0));
$table->add_button("maintenance-update", "Modifier", array(0));
$table->add_button("maintenance-delete", "Supprimer", array(0), false);

$table->show($res);

$request = "SELECT maintenance_id, planner_name, vehicle_name, planned_start_date, planned_time_needed, was_canceled
                FROM upcoming_maintenances('2 months')";
$res = query($request);

$table = new Table("upcoming-maintenance", "Maintenances à venir dans les 2 prochains mois");

$table->set_hidden_fields(array(0, 5));

$table->add_button("maintenance-details", "Détails", array(0));
$table->add_button("maintenance-update", "Modifier", array(0));
$table->add_button("maintenance-delete", "Supprimer", array(0), false);

$table->show($res);

$request = "SELECT prescription_id, vehicle_id, client_id, planner_name, \"intervention_name(s)\", to_do_before_date, model_name, client_name
            FROM prescriptions_view";
$res = query($request);

$table = new Table("maintenance-to-plan", "Maintenances à programmer en lien avec une prescription");

$table->set_hidden_fields(array(0, 1, 2));

$table->add_button("vehicule-link", "Accéder au véhicule", array(1, 2));
// $table->add_button("maintenance-update", "Modifier", array(0));
// $table->add_button("maintenance-delete", "Supprimer", array(0), false);

// $table->add_column("Status", "get_maintenance_status", array(0));

$table->show($res);


cardFooter();
?>