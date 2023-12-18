<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
require_once "../utils/field_names.php";

loadJS('./pages/clients.js');

cardHeader("Clients");

echo "<p>Table des clients :</p>";


$request = "SELECT client_id, client_first_name, client_last_name, client_address, client_email, client_mobile
            FROM clients
            ORDER BY client_id;";
$res = query($request);

function get_sum_of_expenses($client_id)
{
    $requete = "SELECT CONCAT(COALESCE(SUM(total_price), 0), '€') AS total FROM clients
                INNER JOIN vehicles USING (client_id)
                INNER JOIN maintenances USING (vehicle_id)
                WHERE client_id = $1;";
    $res = query($requete, array($client_id));
    return pg_fetch_array($res)[0];
}

function get_nb_vehicle($client_id)
{
    $requete = "SELECT COUNT(v.client_id), c.client_id AS \"nb\" FROM clients c
                LEFT JOIN vehicles v ON c.client_id = v.client_id
                GROUP BY c.client_id
                HAVING c.client_id = $1;";
    $res = query($requete, array($client_id));
    return pg_fetch_array($res)[0];
}

$table = new Table("client-list");

$table->set_hidden_fields(array(0));

$table->add_column("Véhicules", "get_nb_vehicle", array(0));
$table->add_column("Total dépenses", "get_sum_of_expenses", array(0));

$table->add_button("client-details", "Détails", array(0));
$table->add_button("client-update", "Modifier", array(0));
$table->add_button("client-delete", "Supprimer", array(0), false);

$table->show($res);

?>

<a href="#" class="btn btn-primary" id="newClient">
    Ajouter un client
</a>

<?php
cardFooter();
?>