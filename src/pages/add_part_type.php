<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_part_type.js');

cardHeader("Ajout d'un type de pièce");
?>

<form class="row g-3" id="addPartForm">
    
    <div class="col-md-6">
        <label for="part_type_name" class="form-label">Nouveau type de pièce</label>
        <input type="text" class="form-control" id="part_type_name">
    </div>
            
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addPartType">Valider</button>
    </div>

</form>

<?php
cardFooter();
?>
