<!-- Required GET parameters: client_id, vehicle_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_maintenance.js');

$client_id = parseGet('client_id');
$vehicle_id = parseGet('vehicle_id');

clientVehicleCard($client_id, $vehicle_id);

cardHeader("Ajout d'une maintenance");
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
                echo "<option value='" . htmlspecialchars($planners[0]) . "'>" . htmlspecialchars($planners[1]) . " " . htmlspecialchars($planners[2]) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-6">
        <label for="status" class="form-label">Status</label>
        <select class="selectpicker form-control" data-live-search="true" id="status">
            <option value="running" selected>À venir / En cours</option>
            <option value="done">Terminé</option>
            <option value="canceled">Annulé</option>
        </select>
    </div>
    <div class="col-6">
        <label for="start_date" class="form-label">Date de début</label>
        <input type="date" class="form-control" id="start_date">
    </div>
    <div class="col-6">
        <label for="duration" class="form-label">Durée prévue</label>
        <div class="input-group">
            <input type="number" class="form-control" id="duration" min="0" step="0.25">
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
                echo "<li class='list-group-item'>
                        <input class='form-check-input me-1' type='checkbox' value='" . htmlspecialchars($interventions[0]) . "' id='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>
                        <label class='form-check-label stretched-link' for='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>" . htmlspecialchars($interventions[1]) . "</label>
                    </li>";
            }
            ?>
        </ul>
    </div>
    <div class="col-6 col-md-6">
        <label for="total_price" class="form-label">Prix total</label>
        <div class="input-group">
            <input type="number" class="form-control" id="total_price" min="0">
            <div class="input-group-text">€</div>
        </div>
    </div>
    <div class="col-6 col-md-6">
        <label for="mileage" class="form-label">Kilométrage</label>
        <div class="input-group">
            <input type="number" class="form-control" id="mileage" min="0">
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

                    <tbody id='employee_sessions-" . htmlspecialchars($employees[0]) . "'>
                        <!-- Will be filled by JS -->
                    </tbody>
                </table>";
            echo "<button type='button' style='margin: 0 10px 10px 10px' class='btn btn-primary' id='add_session-" . htmlspecialchars($employees[0]) . "'>Ajouter</button>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
        }
        ?>
    </div>
    <hr>
    <div class="col-md-12">
        <label for="maintenance_note" class="form-label">Note(s)</label>
        <textarea class="form-control" id="maintenance_note" style="height: 100px" placeholder="Ajoutez vos remarques ici..."></textarea>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addMaintenance">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>