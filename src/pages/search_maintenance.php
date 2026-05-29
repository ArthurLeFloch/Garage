<!-- Required GET parameters: start_date, end_date -->

<?php
include "../utils/query.php";
include "../utils/parse.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
include "../db-utils/maintenance.php";

loadJS('./pages/search_maintenance.js');

$start_date = parseGet('start_date');
$end_date = parseGet('end_date');


cardHeader("Recherche de maintenances");

echo "<p>Recherche des maintenances entre " . htmlspecialchars($start_date) . " et " . htmlspecialchars($end_date) . "</p><br>";

if (!isDate($start_date)) {
    die("Le format de la date de début est incorrect.");
}
if (!isDate($end_date)) {
    die("Le format de la date de fin est incorrect.");
}

if (isDateBefore($end_date, $start_date)) {
    die("La date de fin doit être après la date de début.");
}

$request = "SELECT maintenance_id, vehicle_id, client_id, STRING_AGG(intervention_name, ' ') AS intervention_name, planned_start_date, total_price FROM vehicles
            INNER JOIN maintenances USING (vehicle_id)
            INNER JOIN maintenances_interventions USING (maintenance_id)
            INNER JOIN interventions USING (intervention_id)
            INNER JOIN clients USING (client_id)
            WHERE planned_start_date BETWEEN $1 AND $2
            GROUP BY maintenance_id, vehicle_id, client_id, planned_start_date, total_price
            ORDER BY planned_start_date DESC;";

$res = query($request, array($start_date, $end_date));

$table = new Table("maintenance-list");

$table->set_hidden_fields(array(0, 1, 2));

$table->add_button("maintenance-details", "Détails", array(0, 1, 2));

$table->add_column("Status", "get_maintenance_status", array(0));

$table->show($res);

cardFooter();
?>