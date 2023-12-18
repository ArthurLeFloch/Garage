<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_employee.js');

cardHeader("Ajout d'un employé");
?>

<form class="row g-3" id="addEmployeeForm">
    <div class="col-6">
        <label for="surname" class="form-label">Nom</label>
        <input type="text" class="form-control" id="surname">
    </div>
    <div class="col-6">
        <label for="name" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="name">
    </div>
    <div class="col-12">
        <label for="address" class="form-label">Adresse</label>
        <input type="text" class="form-control" id="address">
    </div>
    <div class="col-6">
        <label for="email" class="form-label">Adresse mail</label>
        <input type="text" class="form-control" id="email">
    </div>
    <div class="col-6">
        <label for="mobile" class="form-label">Téléphone</label>
        <input type="text" class="form-control" id="mobile" placeholder="06 12 34 56 78">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addEmployee">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>