<!-- Required GET parameters: client_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/client_update.js');

cardHeader("Modification des informations sur le client");

$client_id = parseGet('client_id');

$request = "SELECT client_first_name, client_last_name, client_address, client_email, client_mobile FROM clients WHERE client_id = $1";
$res = query($request, array($client_id));

echo "
    <form class='row g-3' id='addClientForm'>
        <div class='col-md-6'>
            <label for='name' class='form-label'>Nom</label>
            <input type='text' class='form-control' id='name' value='" . htmlspecialchars(pg_fetch_result($res, 0, 1)) . "'>
        </div>
        <div class='col-md-6'>
            <label for='surname' class='form-label'>Prénom</label>
            <input type='text' class='form-control' id='surname' value='" . htmlspecialchars(pg_fetch_result($res, 0, 0)) . "'>
        </div>
        <div class='col-12'>
            <label for='address' class='form-label'>Adresse</label>
            <input type='text' class='form-control' id='address' value='" . htmlspecialchars(pg_fetch_result($res, 0, 2)) . "'>
        </div>
        <div class='col-md-6'>
            <label for='email' class='form-label'>Adresse mail</label>
            <input type='text' class='form-control' id='email' value='" . htmlspecialchars(pg_fetch_result($res, 0, 3)) . "'>
        </div>
        <div class='col-md-6'>
            <label for='mobile' class='form-label'>Téléphone</label>
            <input type='text' class='form-control' id='mobile' value='" . htmlspecialchars(pg_fetch_result($res, 0, 4)) . "'>
        </div>
        <div class='col-12'>
            <button type='submit' class='btn btn-primary' id='submitUpdateClient'>Valider</button>
        </div>
    </form>";

cardFooter();

?>
