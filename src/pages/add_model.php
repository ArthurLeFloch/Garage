<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_model.js');

cardHeader("Ajout d'un modèle de véhicule");
?>

<form class="row g-3" id="addEmployeeForm">
    
    <div class="col-6">
        <label for="model-name" class="form-label">Nom du modèle</label>
        <input type="text" class="form-control" id="model-name">
    </div>
    <div class="col-6">
        <label for="model-version" class="form-label">Version du modèle</label>
        <input type="text" class="form-control" id="model-version">
    </div>

    <div class="col-md-4 col-6">
        <label for="model-type-name" class="form-label">Nom du type de modèle</label>
        <select class="selectpicker form-control" data-live-search="true" id="model-type-name">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT model_type_id, model_type_name FROM model_types;";
            $res = query($request);
            while ($model_types = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($model_types[0]) . "'>" . htmlspecialchars($model_types[1]) . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-4 col-6">
        <label for="fuel-type" class="form-label">Carburant</label>
        <select class="selectpicker form-control" data-live-search="true" id="fuel-type">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Carburant';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
            }
            ?>
        </select>
    </div>


    <div class="col-md-4 col-6">
        <label for="coolant-type" class="form-label">Liquide de refroidissement</label>
        <select class="selectpicker form-control" data-live-search="true" id="coolant-type">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Liquide de refroidissement';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-4 col-6">
        <label for="suspension-type" class="form-label">Suspension</label>
        <select class="selectpicker form-control" data-live-search="true" id="suspension-type">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Suspension';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-4 col-6">
        <label for="wheel-type" class="form-label">Roue</label>
        <select class="selectpicker form-control" data-live-search="true" id="wheel-type">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Roue';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-4 col-6">
        <label for="oil-type" class="form-label">Huile</label>
        <select class="selectpicker form-control" data-live-search="true" id="oil-type">
            <option value="" disabled selected hidden>Choisir...</option>
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Huile';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
            }
            ?>
        </select>
    </div>
            
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addModel">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>