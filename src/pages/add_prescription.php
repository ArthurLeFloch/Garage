<!-- Required GET parameters: client_id, vehicle_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_prescription.js');

$client_id = parseGet('client_id');
$vehicle_id = parseGet('vehicle_id');

clientVehicleCard($client_id, $vehicle_id);

cardHeader("Ajout d'une prescription");
?>

<form class="row g-3">
    <div class="col-md-12">
        <label for="intervention" class="form-label">Intervention</label>
        <select class="selectpicker form-control" data-live-search="true" id="intervention">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT intervention_id, intervention_name FROM interventions;";
            $res = query($request);
            while ($interventions = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($interventions[0]) . "'>" . htmlspecialchars($interventions[1]) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="to_do_before_date" class="form-label">À faire avant</label>
        <input type="date" class="form-control" id="to_do_before_date">
    </div>
    <div class="col-md-6">
        <label for="planner" class="form-label">Plannificateur</label>
        <select class="selectpicker form-control" data-live-search="true" id="planner">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT employee_id, employee_first_name, employee_last_name FROM employees
                        ORDER BY employee_first_name;";
            $res = query($request);
            while ($planners = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($planners[0]) . "'>" . htmlspecialchars($planners[1]) . " " . htmlspecialchars($planners[2]) . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addPrescription">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>