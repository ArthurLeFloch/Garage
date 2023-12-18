<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_model_type.js');

cardHeader("Ajout d'un type de modèle");
?>

<form class="row g-3" id="addModelTypeForm">
    <div class="col-md-6">
        <label for="model_type_name" class="form-label">Nom du type de modèle</label>
        <input type="text" class="form-control" id="model_type_name">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addModelType">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>