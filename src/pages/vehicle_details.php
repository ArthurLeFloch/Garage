<!-- Required GET parameters: client_id, vehicle_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../utils/field_names.php";
include "../fragments/utils.php";
include "../fragments/table.php";
include "../db-utils/maintenance.php";

loadJS('./pages/vehicle_details.js');

$client_id = parseGet('client_id');
$vehicle_id = parseGet('vehicle_id');

clientVehicleCard($client_id, $vehicle_id);


cardHeader("Informations sur le véhicule");

$request = "SELECT SUM(total_price) AS total FROM vehicles
			INNER JOIN maintenances USING (vehicle_id)
			WHERE vehicle_id = $1;";
$res = query($request, array($vehicle_id));
$total = pg_fetch_array($res)[0];
echo "<p><b>Total des dépenses sur le véhicule</b> : " . (($total == '') ? '0' : $total) . "€</p>";

$request = "SELECT prescription_id, prescription_date, to_do_before_date, intervention_name, employee_id FROM prescriptions
            INNER JOIN vehicles USING (vehicle_id)
            INNER JOIN prescriptions_interventions USING (prescription_id)
            INNER JOIN interventions USING (intervention_id)
            WHERE vehicle_id = $1";
$res = query($request, array($vehicle_id));

if (pg_num_rows($res) > 0) {
    echo "<div class='alert alert-danger' role='alert'>";
    if (pg_num_rows($res) == 1) {
        echo "<b>Le client a une prescription, il est nécessaire de plannifier une maintenance.</b>";
    } else {
        echo "<b>Le client a des prescriptions, il est nécessaire de plannifier des maintenances.</b>";
    }
    echo "<br><br>";

    $table = new Table("prescription-list");

    $table->set_hidden_fields(array(0, 4));

    $table->add_button("prescription-delete", "Supprimer", array(0), false);

    $table->show($res);
} else {
    echo "<div class='alert alert-primary' role='alert'>
            Aucune prescription pour ce véhicule.
            <br>
        ";
}
?>
<br>
<button type="button" class="btn btn-primary" id="newPrescription">
    Ajouter une prescription
</button>
</div>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-maintenances-tab" data-bs-toggle="tab" data-bs-target="#nav-maintenances" type="button" role="tab" aria-controls="nav-maintenances" aria-selected="true">
            Maintenances
        </button>
        <button class="nav-link" id="nav-extern-tab" data-bs-toggle="tab" data-bs-target="#nav-extern" type="button" role="tab" aria-controls="nav-extern" aria-selected="false">
            Maintenances externes
        </button>
        <button class="nav-link" id="nav-model-tab" data-bs-toggle="tab" data-bs-target="#nav-model" type="button" role="tab" aria-controls="nav-model" aria-selected="false">
            Maintenances sur le modèle
        </button>
        <button class="nav-link" id="nav-type-tab" data-bs-toggle="tab" data-bs-target="#nav-type" type="button" role="tab" aria-controls="nav-type" aria-selected="false">
            Maintenances sur le type
        </button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent" style="margin: 10px 0 10px 0;">
    <div class="tab-pane fade show active" id="nav-maintenances" role="tabpanel" aria-labelledby="nav-maintenances-tab" tabindex="0">
        <p>Maintenances sur le véhicule :</p>
        <br>

        <?php

        $request = "SELECT maintenance_id, 
                        is_finished, 
                        was_canceled, 
                        STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\", 
                        planned_start_date,
                        maintenance_start_date, 
                        planned_time_needed,
                        maintenance_time_spent,
                        CONCAT(COALESCE(SUM(total_price), 0), '€')  AS total_price FROM vehicles
			INNER JOIN maintenances_view USING (vehicle_id)
			INNER JOIN maintenances_interventions USING (maintenance_id)
			INNER JOIN interventions USING (intervention_id)
			WHERE vehicle_id = $1
			GROUP BY maintenance_id, planned_start_date, is_finished, total_price, was_canceled, maintenance_start_date, planned_time_needed, maintenance_time_spent
			ORDER BY planned_start_date DESC;";
        $res = query($request, array($vehicle_id));

        $table = new Table("maintenance-list");

        $table->set_hidden_fields(array(0, 1, 2));

        $table->add_button("maintenance-details", "Détails", array(0));
        $table->add_button("maintenance-update", "Modifier", array(0));
        $table->add_button("maintenance-delete", "Supprimer", array(0), false);

        $table->add_column("Status", "get_maintenance_status", array(0));

        $table->show($res);

        ?>

        <a href="#" class="btn btn-primary" id="newMaintenance">
            Ajouter une maintenance
        </a>
    </div>

    <div class="tab-pane fade" id="nav-extern" role="tabpanel" aria-labelledby="nav-extern-tab" tabindex="0">
        <p>Maintenances externes sur le véhicule :</p>
        <br>

        <?php
        $request = "SELECT extern_maintenance_id, extern_garage_name, STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\", extern_start_date, extern_end_date FROM vehicles
			INNER JOIN extern_maintenances USING (vehicle_id)
			INNER JOIN extern_maintenances_interventions USING (extern_maintenance_id)
			INNER JOIN interventions USING (intervention_id) INNER JOIN extern_garages USING (extern_garage_id)
			WHERE vehicle_id = $1
			GROUP BY extern_maintenance_id, extern_garage_name, extern_start_date, extern_end_date
			ORDER BY extern_start_date DESC;";
        $res = query($request, array($vehicle_id));

        $table = new Table("extern-maintenance-list");

        $table->set_hidden_fields(array(0));

        $table->add_button("extern-maintenance-details", "Détails", array(0));
        $table->add_button("extern-maintenance-update", "Modifier", array(0));
        $table->add_button("extern-maintenance-delete", "Supprimer", array(0), false);

        $table->show($res);

        ?>

        <a href="#" class="btn btn-primary" id="newExternMaintenance">
            Ajouter une maintenance externe
        </a>

    </div>
    <div class="tab-pane fade" id="nav-model" role="tabpanel" aria-labelledby="nav-model-tab" tabindex="0">
        <p>Maintenances récurrentes sur ce modèle :</p>
        <br>

        <?php
        $request = "SELECT recurrent_maintenance_id, STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\", CONCAT(COALESCE(mileage_repeat, 0), ' km') AS mileage_repeat, CONCAT(COALESCE(days_after_last_maintenance, 0), ' jour(s)') AS days_after_last_maintenance, CONCAT(COALESCE(estimated_price, 0), '€') AS estimated_price FROM vehicles
					LEFT JOIN models USING(model_id)
					LEFT JOIN recurrent_maintenances USING(model_id)
					LEFT JOIN recurrent_maintenances_interventions USING(recurrent_maintenance_id)
					INNER JOIN interventions USING(intervention_id)
					LEFT JOIN models_interventions_prices ON
						models_interventions_prices.model_id = vehicles.model_id AND
						models_interventions_prices.intervention_id = interventions.intervention_id
					WHERE vehicle_id = $1
                    AND recurrent_maintenances.model_type_id IS NULL
                    GROUP BY recurrent_maintenance_id, mileage_repeat, days_after_last_maintenance, estimated_price;";
        $res = query($request, array($vehicle_id));

        $table = new Table("model-maintenance-list");

        $table->set_hidden_fields(array(0));

        $table->show($res);

        ?>

    </div>
    <div class="tab-pane fade" id="nav-type" role="tabpanel" aria-labelledby="nav-type-tab" tabindex="0">
        <p>Maintenances récurrentes sur le type de véhicule :</p>
        <br>

        <?php
        $request = "SELECT recurrent_maintenance_id,
                    STRING_AGG(intervention_name, ', ') AS \"intervention_name(s)\",
                    CONCAT(COALESCE(mileage_repeat, 0), ' km') AS mileage_repeat,
                    CONCAT(COALESCE(days_after_last_maintenance, 0), ' jour(s)') AS days_after_last_maintenance, 
                    CONCAT(COALESCE(estimated_price, 0), '€') AS estimated_price
                    FROM vehicles
					LEFT JOIN models USING(model_id)
					LEFT JOIN model_types USING(model_type_id)
					LEFT JOIN recurrent_maintenances ON (recurrent_maintenances.model_type_id = model_types.model_type_id)
					LEFT JOIN recurrent_maintenances_interventions USING(recurrent_maintenance_id)
					INNER JOIN interventions USING(intervention_id)
					LEFT JOIN model_types_interventions_prices ON
						model_types_interventions_prices.model_type_id = model_types.model_type_id AND
						model_types_interventions_prices.intervention_id = interventions.intervention_id
					WHERE vehicle_id = $1
                    AND recurrent_maintenances.model_id IS NULL
                    GROUP BY recurrent_maintenance_id, mileage_repeat, days_after_last_maintenance, estimated_price;";
        $res = query($request, array($vehicle_id));

        $table = new Table("type-maintenance-list");

        $table->set_hidden_fields(array(0));

        $table->show($res);

        ?>
    </div>
</div>

<?php
cardFooter();
?>