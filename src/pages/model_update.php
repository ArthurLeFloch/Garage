<!-- Required GET parameters: model_id -->

<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

$model_id = parseGet('model_id');

loadJS('./pages/model_update.js');

cardHeader("Modification d'un modèle de véhicule");

$request = "SELECT model_id, manufacturer_id, model_type_id, model_name, model_version, coolant_id, suspension_id, wheel_id, oil_id, fuel_id 
            FROM models
            WHERE model_id = $1;";
$res = query($request, array($model_id));
$array = pg_fetch_array($res);
$manufacturer_id = htmlspecialchars($array[1]);
$model_type_id = htmlspecialchars($array[2]);
$model_name = htmlspecialchars($array[3]);
$model_version = htmlspecialchars($array[4]);
$coolant_id = htmlspecialchars($array[5]);
$suspension_id = htmlspecialchars($array[6]);
$wheel_id = htmlspecialchars($array[7]);
$oil_id = htmlspecialchars($array[8]);
$fuel_id = htmlspecialchars($array[9]);

?>

<form class="row g-3">

    <div class="col-6">
        <label for="model-name" class="form-label">Nom du modèle</label>
        <?php
        echo "<input type='text' class='form-control' id='model-name' value='" . $model_name . "'>";
        ?>
    </div>
    <div class="col-6">
        <label for="model-version" class="form-label">Version du modèle</label>
        <?php
        echo "<input type='text' class='form-control' id='model-version' value='" . $model_version . "'>";
        ?>
    </div>

    <div class="col-md-4 col-6">
        <label for="model-type-name" class="form-label">Nom du type de modèle</label>
        <select class="selectpicker form-control" data-live-search="true" id="model-type-name">
            <?php
            $request = "SELECT model_type_id, model_type_name FROM model_types;";
            $res = query($request);
            while ($model_types = pg_fetch_array($res)) {
                if ($model_type_id == $model_types[0]) {
                    echo "<option selected value='" . htmlspecialchars($model_types[0]) . "'>" . htmlspecialchars($model_types[1]) . "</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($model_types[0]) . "'>" . htmlspecialchars($model_types[1]) . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <div class="col-md-4 col-6">
        <label for="fuel-type" class="form-label">Carburant</label>
        <select class="selectpicker form-control" data-live-search="true" id="fuel-type">
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Carburant';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                if ($fuel_id = $models[0]) {
                    echo "<option selected value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                }
            }
            ?>
        </select>
    </div>


    <div class="col-md-4 col-6">
        <label for="coolant-type" class="form-label">Liquide de refroidissement</label>
        <select class="selectpicker form-control" data-live-search="true" id="coolant-type">
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Liquide de refroidissement';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                if ($coolant_id == $models[0]) {
                    echo "<option selected value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-md-4 col-6">
        <label for="suspension-type" class="form-label">Suspension</label>
        <select class="selectpicker form-control" data-live-search="true" id="suspension-type">
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Suspension';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                if ($suspension_id == $models[0]) {
                    echo "<option selected value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-md-4 col-6">
        <label for="wheel-type" class="form-label">Roue</label>
        <select class="selectpicker form-control" data-live-search="true" id="wheel-type">
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Roue';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                if ($wheel_id == $models[0]) {
                    echo "<option selected value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-md-4 col-6">
        <label for="oil-type" class="form-label">Huile</label>
        <select class="selectpicker form-control" data-live-search="true" id="oil-type">
            <?php
            $request = "SELECT part_id, part_name 
                        FROM parts 
                        JOIN part_types USING (part_type_id)
                        WHERE part_type_name='Huile';";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                if ($oil_id == $models[0]) {
                    echo "<option selected value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($models[0]) . "'>" . htmlspecialchars($models[1]) . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updateModel">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>