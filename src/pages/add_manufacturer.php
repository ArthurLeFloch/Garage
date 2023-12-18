<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_manufacturer.js');

cardHeader("Ajout d'un fabricant de véhicule");
?>

<form class="row g-3" id="addManufacturerForm">
    <div class="col-md-6">
        <label for="manufacturer_name" class="form-label">Nom du fabricant</label>
        <input type="text" class="form-control" id="manufacturer_name">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addManufacturer">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>