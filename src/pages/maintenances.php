<?php
include "../utils/query.php";
include "../utils/parse.php";
include "../utils/hydration.php";
include "../utils/field_names.php";
include "../fragments/utils.php";
include "../fragments/table.php";
include "../db-utils/maintenance.php";

loadJS('./pages/maintenances.js');

cardHeader("Maintenances");
?>

<p>Recherche de maintenance :</p>
<form class="row g-3">
    <div class="col-6">
        <label for="start_date" class="form-label">Date de début</label>
        <input type="date" class="form-control" id="start_date">
    </div>
    <div class="col-6">
        <label for="end_date" class="form-label">Date de fin</label>
        <input type="date" class="form-control" id="end_date">
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-primary" id="searchMaintenances">Rechercher</button>
    </div>
</form>
<br>
<hr>
<br>

<p>Maintenances en cours :</p>
<?php

$request = "SELECT maintenance_id, 
                    vehicle_id, 
                    client_id, 
                    STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\", 
                    planned_start_date,
                    maintenance_start_date, 
                    planned_time_needed, 
                    maintenance_time_spent, 
                    CONCAT(mileage_vehicle, ' km') AS mileage_vehicle
                    FROM vehicles
            INNER JOIN maintenances_view USING (vehicle_id)
            INNER JOIN maintenances_interventions USING (maintenance_id)
            INNER JOIN interventions USING (intervention_id)
            INNER JOIN clients USING (client_id)
            WHERE is_finished = FALSE
            AND was_canceled = FALSE
            AND planned_start_date <= NOW()
            GROUP BY maintenance_id, vehicle_id, client_id, planned_start_date, is_finished, total_price, maintenance_start_date, planned_time_needed, maintenance_time_spent, mileage_vehicle
            ORDER BY    planned_start_date DESC,
                        is_finished DESC;";
$res = query($request);

$table = new Table("ongoing-maintenance-list");

$table->set_hidden_fields(array(0, 1, 2));

$table->add_button("maintenance-details", "Détails", array(0));
$table->add_button("maintenance-update", "Modifier", array(0));
$table->add_button("maintenance-delete", "Supprimer", array(0), false);

$table->show($res);
?>

<p>Maintenances à venir :</p>
<?php
$request = "SELECT maintenance_id, vehicle_id, client_id, STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\", planned_start_date, planned_time_needed, CONCAT(mileage_vehicle, ' km') AS mileage_vehicle FROM vehicles
            INNER JOIN maintenances USING (vehicle_id)
            INNER JOIN maintenances_interventions USING (maintenance_id)
            INNER JOIN interventions USING (intervention_id)
            INNER JOIN clients USING (client_id)
            WHERE is_finished = FALSE
            AND planned_start_date > NOW()
            GROUP BY maintenance_id, vehicle_id, client_id, planned_start_date, is_finished, total_price, planned_time_needed, mileage_vehicle
            ORDER BY  planned_start_date ASC;";
$res = query($request);

$table = new Table("coming-maintenance-list");

$table->set_hidden_fields(array(0, 1, 2));

$table->add_button("maintenance-details", "Détails", array(0));
$table->add_button("maintenance-update", "Modifier", array(0));
$table->add_button("maintenance-delete", "Supprimer", array(0), false);

$table->show($res);
?>

<p>Maintenances terminées ou annulées :</p>

<?php
$request = "SELECT maintenance_id, 
                vehicle_id, 
                client_id,
                is_finished,
                was_canceled, 
                STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\", 
                maintenance_start_date, 
                maintenance_end_date, 
                planned_time_needed, 
                maintenance_time_spent, 
                CONCAT(COALESCE(SUM(total_price), 0), '€') AS total_price 
            FROM vehicles
            INNER JOIN maintenances_view USING (vehicle_id)
            INNER JOIN maintenances_interventions USING (maintenance_id)
            INNER JOIN interventions USING (intervention_id)
            INNER JOIN clients USING (client_id)
            WHERE is_finished = TRUE
            OR was_canceled = TRUE
            GROUP BY maintenance_id, vehicle_id, client_id, planned_start_date, is_finished, total_price, maintenance_start_date, maintenance_end_date, planned_time_needed, maintenance_time_spent, mileage_vehicle, was_canceled, total_price
            ORDER BY planned_start_date DESC;";
$res = query($request);

$table = new Table("finished-maintenance-list");

$table->set_hidden_fields(array(0, 1, 2, 3, 4));

$table->add_button("maintenance-details", "Détails", array(0));
$table->add_button("maintenance-update", "Modifier", array(0));
$table->add_button("maintenance-delete", "Supprimer", array(0), false);

$table->add_column("Status", "get_maintenance_status", array(0));

$table->show($res);
?>


<p>Toutes les maintenances récurrentes</p>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-type-tab" data-bs-toggle="tab" data-bs-target="#nav-type" type="button" role="tab" aria-controls="nav-type" aria-selected="true">
            Maintenances récurrentes en fonctions des modèles
        </button>
        <button class="nav-link" id="nav-model-tab" data-bs-toggle="tab" data-bs-target="#nav-model" type="button" role="tab" aria-controls="nav-model" aria-selected="false">
            Maintenances récurrentes en fonctions des familles de modèles
        </button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent" style="margin: 10px 0 10px 0;">
    <div class="tab-pane fade show active" id="nav-type" role="tabpanel" aria-labelledby="nav-type-tab" tabindex="0">
        <br>

        <?php
        $request = "SELECT recurrent_maintenance_id, manufacturer_name, model_name, model_version, part_name AS \"fuel_name\", CONCAT(COALESCE(mileage_repeat, 0), ' km') AS mileage_repeat, CONCAT(COALESCE(days_after_last_maintenance, 0), ' jour(s)') AS days_after_last_maintenance, STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\" 
                    FROM recurrent_maintenances
                    INNER JOIN recurrent_maintenances_interventions USING (recurrent_maintenance_id)
                    INNER JOIN interventions USING (intervention_id)
                    INNER JOIN models USING (model_id)
                    INNER JOIN manufacturers USING (manufacturer_id)
                    INNER JOIN model_types ON (models.model_type_id = model_types.model_type_id)
                    INNER JOIN parts ON (models.fuel_id = parts.part_id)
                    WHERE recurrent_maintenances.model_type_id IS NULL
                    GROUP BY model_name, model_version, part_name, mileage_repeat, days_after_last_maintenance, recurrent_maintenance_id, manufacturer_name
                    ORDER BY model_name, model_version,mileage_repeat ASC, days_after_last_maintenance ASC;";
        $res = query($request);

        $table = new Table("model-rec-maintenance-list");

        $table->set_hidden_fields(array(0));

        $table->add_button("maintenance-rec-model-update", "Modifier", array(0));
        $table->add_button("maintenance-rec-model-delete", "Supprimer", array(0), false);

        $table->show($res);

        ?>

        <br>
        <button type="button" class="btn btn-primary" id="newRecMaintenanceModel">
            Ajouter une maintenance récurrente
        </button>
    </div>

    <div class="tab-pane fade" id="nav-model" role="tabpanel" aria-labelledby="nav-model-tab" tabindex="0">
        <br>

        <?php
        $request = "SELECT recurrent_maintenance_id, recurrent_maintenances.model_type_id, model_type_name, CONCAT(COALESCE(mileage_repeat, 0), ' km') AS mileage_repeat, CONCAT(COALESCE(days_after_last_maintenance, 0), ' jour(s)') AS days_after_last_maintenance, STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\" 
                    FROM recurrent_maintenances
                    INNER JOIN recurrent_maintenances_interventions USING (recurrent_maintenance_id)
                    INNER JOIN interventions USING (intervention_id)
                    INNER JOIN model_types ON (recurrent_maintenances.model_type_id = model_types.model_type_id)
                    WHERE recurrent_maintenances.model_id IS NULL
                    GROUP BY recurrent_maintenance_id, recurrent_maintenances.model_type_id, model_type_name, mileage_repeat, days_after_last_maintenance
                    ORDER BY model_type_name,mileage_repeat ASC, days_after_last_maintenance ASC;";
        $res = query($request);

        $table = new Table("type-model-rec-maintenance-list");

        $table->set_hidden_fields(array(0, 1));

        $table->add_button("maintenance-rec-model-type-update", "Modifier", array(0));
        $table->add_button("maintenance-rec-model-type-delete", "Supprimer", array(0), false);

        $table->show($res);

        ?>

        <br>
        <button type="button" class="btn btn-primary" id="newRecMaintenanceModelType">
            Ajouter une maintenance récurrente
        </button>
    </div>
</div>


<?php
cardFooter();
?>