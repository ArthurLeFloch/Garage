<?php
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/add_client.js');

cardHeader("Ajout d'un client");
?>

<form class="row g-3" id="addClientForm">
    <div class="col-md-6">
        <label for="surname" class="form-label">Nom</label>
        <input type="text" class="form-control" id="surname">
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="name">
    </div>
    <div class="col-12">
        <label for="address" class="form-label">Adresse</label>
        <input type="text" class="form-control" id="address">
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Adresse mail</label>
        <input type="text" class="form-control" id="email">
    </div>
    <div class="col-md-6">
        <label for="mobile" class="form-label">Téléphone</label>
        <input type="text" class="form-control" id="mobile" placeholder="06 12 34 56 78">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addClient">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>