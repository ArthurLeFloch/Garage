<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_recurrent_maintenance_model_type.js');

cardHeader("Ajout d'une maintenance récurrente sur un type de modèle");
?>

<form class="row g-3">
    <div class="col-md-12">
        <label for="model-type" class="form-label">Type de modèle</label>
        <select class="selectpicker form-control" data-live-search="true" id="model-type">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT model_type_id, model_type_name FROM model_types;";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
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
                echo "<li class='list-group-item'>
                        <input class='form-check-input me-1' type='checkbox' value='" . htmlspecialchars($interventions[0]) . "' id='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>
                        <label class='form-check-label stretched-link' for='intervention-checkbox-" . htmlspecialchars($interventions[0]) . "'>" . htmlspecialchars($interventions[1]) . "</label>
                    </li>";
            }
            ?>
        </ul>
    </div>
    <div class="col-md-6">
        <label for="mileage" class="form-label">Tous les</label>
        <div class="input-group">
            <input class="form-control" type="number" min="0" step="1" id="mileage" value="0">
            <div class="input-group-text">kilomètre(s)</div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="days" class="form-label">Tous les</label>
        <div class="input-group">
        <input class="form-control" type="number" min="0" step="1" id="days" value="0">
            <div class="input-group-text">jour(s)</div>
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addRecMaintenanceModelType">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>