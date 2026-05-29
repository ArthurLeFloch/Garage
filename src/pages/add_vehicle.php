<!-- Required GET parameters: client_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_vehicle.js');

clientCard(parseGet('client_id'));

cardHeader("Ajouter un véhicule");
?>

<form class="row g-3" id="addVehicleForm">
    <div class="col-md-5">
        <label for="vin" class="form-label">VIN</label>
        <input type="text" class="form-control" id="vin">
    </div>
    <div class="col-md-3">
        <label for="plate_number" class="form-label">Plaque d'immatriculation</label>
        <input type="text" class="form-control" id="plate_number">
    </div>
    <div class="col-md-4">
        <label for="circulation_date" class="form-label">Date de mise en circulation</label>
        <input type="date" class="form-control" id="circulation_date">
    </div>
    <div class="col-md-12">
        <label for="model_type" class="form-label">Modèle</label>
        <select class="form-select" id="model_type">
            <?php
            $request = "SELECT model_id, manufacturer_name, model_name, model_version, fuel_name FROM models_view mo
            INNER JOIN manufacturers ma ON ma.manufacturer_id = mo.manufacturer_id
            INNER JOIN model_types USING (model_type_id)
            ORDER BY mo.model_name, mo.model_version;";
            $res = query($request);
            while ($models = pg_fetch_array($res)) {
                $fullname = "";
                for ($i = 1; $i < 3; $i++) {
                    $fullname .= htmlspecialchars($models[$i]) . " ";
                }
                $fullname .= "- ". htmlspecialchars($models[$i]) . " ";
                $fullname .= "(". htmlspecialchars($models[$i+1]) . ")";
                echo "<option value='" . htmlspecialchars($models[0]) . "'>" . $fullname . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addVehicle">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>