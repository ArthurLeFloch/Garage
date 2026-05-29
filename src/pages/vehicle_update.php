<!-- Required GET parameters: client_id, vehicle_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/vehicle_update.js');

$client_id = parseGet('client_id');
$vehicle_id = parseGet('vehicle_id');

clientCard($client_id);


cardHeader("Informations sur le véhicule");

$requete = "SELECT v.vin_number, v.plate_number, v.registration_date, model_name, manufacturer_name, v.model_id FROM vehicles v
            INNER JOIN models m ON v.model_id = m.model_id
            INNER JOIN manufacturers ma ON m.manufacturer_id = ma.manufacturer_id
            WHERE vehicle_id = $1
            LIMIT 1;";
$res = query($requete, array($vehicle_id));

echo "
    <form class='row g-3' id='addVehicleForm'>
        <div class='col-md-5'>
            <label for='vin' class='form-label'>VIN</label>
            <input type='text' class='form-control' id='vin' value='" . htmlspecialchars(pg_fetch_result($res, 0, 0)) . "'>
        </div>
        <div class='col-md-3'>
            <label for='plate_number' class='form-label'>Plaque d'immatriculation</label>
            <input type='text' class='form-control' id='plate_number' value='" . htmlspecialchars(pg_fetch_result($res, 0, 1)) . "'>
        </div>
        <div class='col-md-4'>
            <label for='circulation_date' class='form-label'>Date de mise en circulation</label>
            <input type='date' class='form-control' id='circulation_date' value='" . htmlspecialchars(pg_fetch_result($res, 0, 2)) . "'>
        </div>
        <div class='col-md-12'>
            <label for='model_type' class='form-label'>Modèle</label>
            <select class='form-select' id='model_type' value='" . htmlspecialchars(pg_fetch_result($res, 0, 0)) . "'>
";
            $request2 = "SELECT model_id, manufacturer_name, model_name, model_version, fuel_name FROM models_view mo
            INNER JOIN manufacturers ma ON ma.manufacturer_id = mo.manufacturer_id 
            ORDER BY mo.model_name, mo.model_version;";
            $res2 = query($request2);
            while ($models = pg_fetch_array($res2)) {
                $fullname = "";
                for ($i = 1; $i < 3; $i++) {
                    $fullname .= htmlspecialchars($models[$i]) . " ";
                }
                $fullname .= "- ". htmlspecialchars($models[$i]) . " ";
                $fullname .= "(". htmlspecialchars($models[$i+1]) . ")";
                if ($models[0] == pg_fetch_result($res, 0, 5)) {
                    echo "<option value='" . htmlspecialchars($models[0]) . "' selected>" . $fullname . "</option>";
                } else {
                    echo "<option value='" . htmlspecialchars($models[0]) . "'>" . $fullname . "</option>";				
                }
            }

    echo "</select>
        </div>

        <div class='col-12'>
            <button type='submit' class='btn btn-primary' id='submitVehicleUpdate'>Valider</button>
        </div>
    </form>
    ";

cardFooter();
?>