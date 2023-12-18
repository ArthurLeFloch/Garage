<?php
include "../utils/hydration.php";
include "../utils/parse.php";
include "../utils/query.php";
include "../fragments/utils.php";

loadJS('./pages/model_type_update.js');

$model_type_id = parseGet("model_type_id");

$request = "SELECT model_type_name FROM model_types WHERE model_type_id = $1;";
$res = query($request, array($model_type_id));
$array = pg_fetch_array($res);
$model_type_name = htmlspecialchars($array[0]);

cardHeader("Modification d'un type de modèle");
?>

<form class="row g-3">
    <div class="col-md-6">
        <label for="model_type_name" class="form-label">Nom du type de modèle</label>
        <?php
		echo "<input type='text' class='form-control' id='model_type_name' value='" . $model_type_name . "'>";
		?>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updateModelType">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>