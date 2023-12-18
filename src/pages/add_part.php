<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_part.js');

$part_type_id = parseGet("part_type_id");

$request = "SELECT part_type_name FROM part_types WHERE part_type_id = $1;";
$res = query($request, array($part_type_id));
$array = pg_fetch_array($res);
$part_type_name = htmlspecialchars($array[0]);

cardHeader("Ajout d'une pièce - " . $part_type_name);
?>

<form class="row g-3" id="addPartForm">

    <div class="col-md-4 col-12">
        <label for="part_name" class="form-label">Nom de la pièce</label>
        <input type="text" class="form-control" id="part_name">
    </div>


    <div class="col-md-4 col-6">
        <label for="part_reference" class="form-label">Référence de la pièce</label>
        <input type="text" class="form-control" id="part_reference">
    </div>


    <div class="col-md-4 col-6">
        <label for="unitary_price" class="form-label">Prix unitaire</label>
        <input type="number" class="form-control" id="unitary_price">
    </div>
            
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addPart">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>
