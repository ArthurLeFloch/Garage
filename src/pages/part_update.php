<?php
include "../utils/hydration.php";
include "../utils/query.php";
include "../utils/parse.php";
include "../fragments/utils.php";

loadJS('./pages/part_update.js');

$part_type_id = parseGet("part_type_id");

$request = "SELECT part_type_name FROM part_types WHERE part_type_id = $1;";
$res = query($request, array($part_type_id));
$array = pg_fetch_array($res);
$part_type_name = $array[0];

cardHeader("Modification d'une pièce - " . $part_type_name);

$part_id = parseGet('part_id');

$request = "SELECT part_type_id, part_type_name, part_name, part_reference, unitary_price FROM parts
            INNER JOIN part_types USING (part_type_id)
            WHERE part_id = $1;";
$res = query($request, array($part_id));
$array = pg_fetch_array($res);
$part_type_id = htmlspecialchars($array[0]);
$part_type_name = htmlspecialchars($array[1]);
$part_name = htmlspecialchars($array[2]);
$part_reference = htmlspecialchars($array[3]);
$unitary_price = htmlspecialchars($array[4]);
?>

<form class="row g-3">

    <div class="col-md-4 col-12">
        <label for="part_name" class="form-label">Nom de la pièce</label>
        <?php
        echo "<input type='text' class='form-control' id='part_name' value='" . $part_name . "'>";
        ?>
    </div>


    <div class="col-md-4 col-6">
        <label for="part_reference" class="form-label">Référence de la pièce</label>
        <?php
        echo "<input type='text' class='form-control' id='part_reference' value='" . $part_reference . "'>";
        ?>
    </div>


    <div class="col-md-4 col-6">
        <label for="unitary_price" class="form-label">Prix unitaire</label>
        <?php
        echo "<input type='number' class='form-control' id='unitary_price' value='" . $unitary_price . "'>";
        ?>
    </div>
            
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updatePart">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>
