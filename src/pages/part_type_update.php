<?php
include "../utils/hydration.php";
include "../utils/parse.php";
include "../utils/query.php";
include "../fragments/utils.php";

loadJS('./pages/part_type_update.js');

cardHeader("Ajout d'un type de pièce");

$part_type_id = parseGet("part_type_id");

$request = "SELECT part_type_id, part_type_name
            FROM part_types
			WHERE part_type_id = $1;";
$res = query($request, array($part_type_id));
$array = pg_fetch_array($res);
$part_type_name = htmlspecialchars($array[1]);

?>

<form class="row g-3" id="addPartForm">
    
    <div class="col-md-6">
        <label for="part_type_name" class="form-label">Nouveau type de pièce</label>
		<?php
        echo "<input type='text' class='form-control' id='part_type_name' value='" . $part_type_name . "'>";
		?>
    </div>
            
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updatePartType">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>
