<!-- Required GET parameters: client_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/field_names.php";
include "../utils/hydration.php";
include "../fragments/utils.php";
include "../fragments/table.php";

loadJS('./pages/client_details.js');

$client_id = parseGet('client_id');

clientCard($client_id);


cardHeader("Informations sur le client");

$request = "SELECT v.vehicle_id, model_name, manufacturer_name, plate_number FROM clients c
            INNER JOIN vehicles v ON c.client_id = v.client_id
            INNER JOIN models m ON v.model_id = m.model_id
            INNER JOIN manufacturers ma ON m.manufacturer_id = ma.manufacturer_id
            WHERE c.client_id = $1
            ORDER BY client_last_name ASC;";
$res = query($request, array($client_id));

function get_maintenance_cost($vehicle_id)
{
    $request = "SELECT CONCAT(COALESCE(SUM(total_price), 0), '€') AS total FROM maintenances WHERE vehicle_id = $1;";
    $res = query($request, array($vehicle_id));
    return pg_fetch_array($res)[0];
}

$table = new Table("vehicle-list");

$table->set_hidden_fields(array(0));

$table->add_button("vehicle-details", "Détails", array(0));
$table->add_button("vehicle-update", "Modifier", array(0));
$table->add_button("vehicle-delete", "Supprimer", array(0), false);

$table->add_column("Coût de maintenance", "get_maintenance_cost", array(0));

$table->show($res);


$request = "SELECT SUM(total_price) AS total FROM clients
            INNER JOIN vehicles USING (client_id)
            INNER JOIN maintenances USING (vehicle_id)
            WHERE client_id = $1;";
$res = query($request, array($client_id));
$total = pg_fetch_array($res)[0];

echo "<p><b>Total des dépenses</b> : " . (($total == '') ? '0' : $total) . "€</p>";

?>

<a href="#" class="btn btn-primary" id="newVehicle">
    Ajouter un véhicule
</a>

<?php
cardFooter();
?>