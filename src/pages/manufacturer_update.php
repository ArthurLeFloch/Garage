<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/manufacturer_update.js');

$manufacturer_id = parseGet("manufacturer_id");

$request = "SELECT manufacturer_name FROM manufacturers WHERE manufacturer_id = $1;";
$res = query($request, array($manufacturer_id));
$array = pg_fetch_array($res);
$manufacturer_name = htmlspecialchars($array[0]);

cardHeader("Modification d'un fabricant de véhicule");
?>

<form class="row g-3">
    <div class="col-md-6">
        <label for="manufacturer_name" class="form-label">Nom du fabricant</label>
		<?php
        echo "<input type='text' class='form-control' id='manufacturer_name' value='" . $manufacturer_name . "'>";
		?>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updateManufacturer">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>