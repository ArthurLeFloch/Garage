<!-- Required GET parameters: client_id, vehicle_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_extern_maintenance.js');

$client_id = parseGet('client_id');
$vehicle_id = parseGet('vehicle_id');

clientVehicleCard($client_id, $vehicle_id);

cardHeader("Ajout d'une maintenance externe");
?>

<form class="row g-3">
    <div class="col-md-12">
        <label for="garage" class="form-label">Garage</label>
        <input class="form-control" type="text" list="garage-list" id="garage">
        <datalist class="form-select" id="garage-list" style="display: none;">
            <?php
            $request = "SELECT extern_garage_name FROM extern_garages
						ORDER BY extern_garage_name ASC;";
            $res = query($request);
            while ($garages = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($garages[0]) . "'>" . htmlspecialchars($garages[0]) . "</option>";
            }
            ?>
        </datalist>
    </div>
    <div class="col-md-12">
        <label for="intervention" class="form-label">Type d'intervention</label>
        <ul class="list-group" id="checkbox_container">
            <?php
            $request = "SELECT intervention_id, intervention_name FROM interventions
                        ORDER BY intervention_name ASC;";
            $res = query($request);
            while ($interventions = pg_fetch_array($res)) {
                echo "<li class='list-group-item'>
                        <input class='form-check-input me-1' type='checkbox' value='" . htmlspecialchars($interventions[0]) . "' id='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>
                        <label class='form-check-label stretched-link' for='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>" . htmlspecialchars($interventions[1]) . "</label>
                    </li>";
            }
            ?>
        </ul>
    </div>
    <div class="col-6">
        <label for="start_date" class="form-label">Date de début</label>
        <input type="date" class="form-control" id="start_date">
    </div>
    <div class="col-6">
        <label for="end_date" class="form-label">Date de fin</label>
        <input type="date" class="form-control" id="end_date">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addExternMaintenance">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>