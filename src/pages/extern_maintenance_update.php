<!-- Required GET parameters: extern_maintenance_id, client_id, vehicle_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/extern_maintenance_update.js');

$extern_maintenance_id = parseGet('extern_maintenance_id');
$data = "SELECT extern_maintenance_id, extern_garage_id, extern_start_date, extern_end_date FROM extern_maintenances WHERE extern_maintenance_id = $1;";
$res = query($data, array($extern_maintenance_id));
$array = pg_fetch_array($res);
$extern_maintenance_id = $array[0];
$extern_garage_id = $array[1];
$extern_start_date = $array[2];
$extern_end_date = $array[3];

$client_id = parseGet('client_id');
$vehicle_id = parseGet('vehicle_id');
clientVehicleCard($client_id, $vehicle_id);

cardHeader("Modifier une maintenance externe");
?>

<form class="row g-3">
    <div class="col-md-12">
        <label for="garage" class="form-label">Garage</label>
        <?php
        $request = "SELECT extern_garage_name FROM extern_garages
                    WHERE extern_garage_id = $1;";
        $res = query($request, array($extern_garage_id));
        $array = pg_fetch_array($res);
        echo "<input disabled class='form-control' type='text' list='garage-list' id='garage' value='" . htmlspecialchars($array[0]) . "'>";
        ?>

    </div>
    <div class="col-md-12">
        <label for="intervention" class="form-label">Type d'intervention</label>
        <ul class="list-group" id="checkbox_container">
        <?php
            $request = "SELECT intervention_id, intervention_name FROM interventions
                        ORDER BY intervention_name ASC;";
            $res = query($request);
            while ($interventions = pg_fetch_array($res)) {
                $request = "SELECT intervention_id FROM extern_maintenances_interventions WHERE intervention_id = $1 AND extern_maintenance_id = $2;";
                $res2 = query($request, array($interventions[0], $extern_maintenance_id));
                $checked = pg_num_rows($res2) > 0 ? "checked" : "";

                echo "<li class='list-group-item'>
                        <input class='form-check-input me-1' type='checkbox' value='" . htmlspecialchars($interventions[0]) . "' id='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "' $checked>
                        <label class='form-check-label stretched-link' for='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>" . htmlspecialchars($interventions[1]) . "</label>
                    </li>";
            }
            ?>
        </ul>
    </div>
    <div class="col-6">
        <label for="start_date" class="form-label">Date de début</label>
        <?php
        echo "<input type='date' class='form-control' id='start_date' value='" . htmlspecialchars($extern_start_date) . "'>";
        ?>
    </div>
    <div class="col-6">
        <label for="end_date" class="form-label">Date de fin</label>
        <?php
        echo "<input type='date' class='form-control' id='end_date' value='" . htmlspecialchars($extern_end_date) . "'>";
        ?>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updateExternMaintenance">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>