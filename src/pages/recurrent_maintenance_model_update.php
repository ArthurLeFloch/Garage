<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/recurrent_maintenance_model_update.js');

cardHeader("Modification d'une maintenance récurrente sur modèle");

$maintenance_id = parseGet('maintenance_id');

$requete = "SELECT model_id, intervention_id, mileage_repeat, days_after_last_maintenance
            FROM recurrent_maintenances
            INNER JOIN recurrent_maintenances_interventions USING (recurrent_maintenance_id)
            WHERE recurrent_maintenance_id = $1;";
$res = query($requete, array($maintenance_id));
$array = pg_fetch_array($res);
$model_id = $array[0];
$intervention_id = htmlspecialchars($array[1]);
$mileage_repeat = htmlspecialchars($array[2]);
$days_after_last_maintenance = htmlspecialchars($array[3]);
?>

<form class="row g-3">
    <div class="col-md-12">
        <label for="model" class="form-label">Modèle</label>
        <select class="selectpicker form-control" data-live-search="true" id="model">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request2 = "SELECT model_id,
                                model_name, 
                                model_version,
                                part_name
                        FROM models
                        JOIN parts ON models.fuel_id = parts.part_id;";
            $res2 = query($request2);
            while ($models = pg_fetch_array($res2)) {
                if ($models[0] == $model_id) {
                    echo "<option value='" . htmlspecialchars($models[0]) . "' selected>" . htmlspecialchars($models[1]) . " " . htmlspecialchars($models[2]) . " (" . htmlspecialchars($models[3]) . ")</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . " " . htmlspecialchars($models[2]) . " (" . htmlspecialchars($models[3]) . ")</option>";				
                }
            }
            ?>
        </select>
    </div>

    <div class="col-md-12">
        <label for="intervention" class="form-label">Type d'intervention</label>
        <ul class="list-group" id="checkbox_container">
            <?php
            $request = "SELECT intervention_id, intervention_name FROM interventions
                        ORDER BY intervention_name ASC;";
            $res = query($request);
            while ($interventions = pg_fetch_array($res)) {
                $request = "SELECT intervention_id FROM recurrent_maintenances_interventions WHERE intervention_id = $1 AND recurrent_maintenance_id = $2;";
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
    <div class="col-md-6">
        <label for="mileage" class="form-label">Tous les</label>
        <div class="input-group">
            <?php
            echo "<input class='form-control' type='number' min='0' step='1' id='mileage' value='" . (($mileage_repeat == '') ? 0 : $mileage_repeat) ."'>";
            ?>
            <div class="input-group-text">kilomètre(s)</div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="days" class="form-label">Tous les</label>
        <div class="input-group">
        <?php
        echo "<input class='form-control' type='number' min='0' step='1' id='days' value='". (($days_after_last_maintenance == '') ? 0 : $days_after_last_maintenance) ."'>";
        ?>
            <div class="input-group-text">jour(s)</div>
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updateMaintenanceRecModel">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>