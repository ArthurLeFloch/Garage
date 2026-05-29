<!-- Required GET parameters: maintenance_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/maintenance_update.js');

$maintenance_id = parseGet('maintenance_id');
$data = "SELECT client_id, vehicle_id, planner_id, planned_start_date, mileage_vehicle, total_price, is_finished, was_canceled, maintenance_note, planned_time_needed FROM maintenances
        INNER JOIN vehicles USING (vehicle_id)
        INNER JOIN clients USING (client_id)
        WHERE maintenance_id = $1;";
$res = query($data, array($maintenance_id));
$array = pg_fetch_array($res);
$client_id = $array[0];
$vehicle_id = $array[1];
$planner_id = $array[2];
$planned_start_date = $array[3];
$mileage_vehicle = $array[4];
$total_price = $array[5];
$is_finished = $array[6];
$was_canceled = $array[7];
$maintenance_note = $array[8];
$planned_time_needed = $array[9];

$duration = intval(explode(":", $planned_time_needed)[0]);

clientVehicleCard($client_id, $vehicle_id);


cardHeader("Modifier une maintenance");
?>

<form class="row g-3">
    <div class="col-6">
        <label for="planner" class="form-label">Plannificateur</label>
        <select class="selectpicker form-control" data-live-search="true" id="planner">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT employee_id, employee_first_name, employee_last_name FROM employees
                        ORDER BY employee_first_name;";
            $res = query($request);
            while ($planners = pg_fetch_array($res)) {
                if ($planners[0] != $planner_id) {
                    echo "<option value='" . htmlspecialchars($planners[0]) . "'>" . htmlspecialchars($planners[1]) . " " . htmlspecialchars($planners[2]) . "</option>";
                } else {
                    echo "<option selected value='" . htmlspecialchars($planners[0]) . "'>" . htmlspecialchars($planners[1]) . " " . htmlspecialchars($planners[2]) . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-6">
        <label for="status" class="form-label">Status</label>
        <select class="selectpicker form-control" data-live-search="true" id="status">
            <?php
            if ($was_canceled == "t") {
                echo "<option value='running'>À venir / En cours</option>
                  <option value='done'>Terminé</option>
                  <option value='canceled' selected>Annulé</option>";
            } else if ($is_finished == "t") {
                echo "<option value='running'>À venir / En cours</option>
                  <option value='done' selected>Terminé</option>
                  <option value='canceled'>Annulé</option>";
            } else {
                echo "<option value='running' selected>À venir / En cours</option>
                  <option value='done'>Terminé</option>
                  <option value='canceled'>Annulé</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-6">
        <label for="start_date" class="form-label">Date de début</label>
        <?php
        echo "<input type='date' class='form-control' id='start_date' value='" . htmlspecialchars($planned_start_date) . "'>";
        ?>
    </div>
    <div class="col-6">
        <label for="duration" class="form-label">Durée prévue</label>
        <div class="input-group">
            <?php
            echo "<input type='number' class='form-control' id='duration' min='0' step='0.25' value='" . htmlspecialchars($duration) . "'>";
            ?>
            <div class="input-group-text">h</div>
        </div>
    </div>
    <div class="col-md-12">
        <label for="intervention" class="form-label">Type d'intervention</label>
        <ul class="list-group" id="checkbox_container">
            <?php
            $request = "SELECT intervention_id, intervention_name FROM interventions
                        ORDER BY intervention_name ASC;";
            $res = query($request);
            while ($interventions = pg_fetch_array($res)) {
                $request = "SELECT intervention_id FROM maintenances_interventions WHERE intervention_id = $1 AND maintenance_id = $2;";
                $res2 = query($request, array($interventions[0], $maintenance_id));
                $checked = pg_num_rows($res2) > 0 ? "checked" : "";

                echo "<li class='list-group-item'>
                        <input class='form-check-input me-1' type='checkbox' value='" . htmlspecialchars($interventions[0]) . "' id='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "' $checked>
                        <label class='form-check-label stretched-link' for='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>" . htmlspecialchars($interventions[1]) . "</label>
                    </li>";
            }
            ?>
        </ul>
    </div>
    <div class="col-6 col-md-6">
        <label for="total_price" class="form-label">Prix total</label>
        <div class="input-group">
            <?php
            echo "<input type='number' class='form-control' id='total_price' min='0' value='" . htmlspecialchars($total_price) . "' placeholder='" . htmlspecialchars($total_price) . "'>";
            ?>
            <div class="input-group-text">€</div>
        </div>
    </div>
    <div class="col-6 col-md-6">
        <label for="mileage" class="form-label">Kilométrage</label>
        <div class="input-group">
            <?php
            echo "<input type='number' class='form-control' id='mileage' min='0' value='" . htmlspecialchars($mileage_vehicle) . "'>";
            ?>
            <div class="input-group-text">km</div>
        </div>
    </div>
    <br>
    <hr>
    <div class="col-md-12" id="employees_sessions_container">
        <p>Sessions de travail</p>
        <?php
        $request = "SELECT employee_id, employee_first_name, employee_last_name FROM employees
                    ORDER BY employee_first_name, employee_last_name;";
        $res = query($request);
        while ($employees = pg_fetch_array($res)) {
            $data = "SELECT
                        MAKE_DATE(year_number, month_number, day_of_month) as work_date,
                        start_hour::TIME as start_time,
                        (start_hour + work_duration)::TIME as end_time
                        FROM employees_work_histories
                        INNER JOIN timeslots_ym USING (timeslot_ym_id)
                        INNER JOIN timeslots_dh USING (timeslot_dh_id)
                        INNER JOIN work_durations USING (work_duration_id)
                        WHERE maintenance_id = $1 AND employee_id = $2;";

            echo "<div class='card border-dark'>";
            echo "<div class='card-header text-center'>" . htmlspecialchars($employees[1]) . " " . htmlspecialchars($employees[2]) . "</div>";
            echo "<div class='card-body' style='padding: 0; overflow-x: auto;'>";
            echo "<table class='table table-striped' style='text-align: center'>
                    <thead>
                        <tr>
                            <th scope='col'>Date</th>
                            <th scope='col'>Heure de début</th>
                            <th scope='col'>Heure de fin</th>
                            <th scope='col'></th>
                        </tr>
                    </thead>

                    <tbody id='employee_sessions-" . htmlspecialchars($employees[0]) . "'>";
            $res2 = query($data, array($maintenance_id, $employees[0]));
            while ($array = pg_fetch_array($res2)) {
                echo "<tr>";
                echo "<td>";
                echo "<input type='date' class='form-control' id='work_date' value='" . htmlspecialchars($array[0]) . "'>";
                echo "</td>";
                echo "<td>";
                echo "<input type='time' class='form-control' id='start_time' value='" . htmlspecialchars($array[1]) . "'>";
                echo "</td>";
                echo "<td>";
                echo "<input type='time' class='form-control' id='end_time' value='" . htmlspecialchars($array[2]) . "'>";
                echo "</td>";
                echo "<td>";
                echo "<button type='button' class='btn btn-danger' id='delete_session-" . htmlspecialchars($employees[0]) . "-" . htmlspecialchars($array[0]) . "'>Supprimer</button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<button type='button' style='margin: 0 10px 10px 10px' class='btn btn-primary' id='add_session-" . htmlspecialchars($employees[0]) . "'>Ajouter</button>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
        }

        ?>
    </div>
    <br>
    <hr>
    <div class="col-md-12">
        <label for="maintenance_note" class="form-label">Note(s)</label>
        <?php
        echo "<textarea class='form-control' id='maintenance_note' style='height: 100px' placeholder='Ajoutez vos remarques ici...'>" . htmlspecialchars($maintenance_note) . "</textarea>";
        ?>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updateMaintenance">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>