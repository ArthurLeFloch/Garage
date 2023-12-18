<!-- Required GET parameters: extern_maintenance_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/field_names.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

$extern_maintenance_id = parseGet('extern_maintenance_id');
$request = "SELECT client_id, vehicle_id FROM extern_maintenances 
            INNER JOIN vehicles USING (vehicle_id)
            INNER JOIN clients USING (client_id)
            WHERE extern_maintenance_id = $1";
$res = query($request, array($extern_maintenance_id));
$array = pg_fetch_array($res);
$client_id = $array[0];
$vehicle_id = $array[1];

clientVehicleCard($client_id, $vehicle_id);


cardHeader("Informations sur la maintenance");

$request = "SELECT STRING_AGG(intervention_name, ', '), extern_start_date, extern_end_date, extern_garage_name FROM extern_maintenances
            INNER JOIN extern_garages USING (extern_garage_id)
            INNER JOIN extern_maintenances_interventions USING (extern_maintenance_id)
            INNER JOIN interventions USING (intervention_id)
            WHERE extern_maintenance_id = $1
            GROUP BY extern_maintenance_id, extern_start_date, extern_end_date, extern_garage_name
            ORDER BY extern_start_date DESC;";
$res = query($request, array($extern_maintenance_id));
$array = pg_fetch_array($res);
echo "<p><b>Garage</b> : " . htmlspecialchars($array[3]) . "</p>";
echo "<p><b>Interventions réalisées</b> : " . htmlspecialchars($array[0]) . "</p>";
echo "<p><b>Date de début</b> : " . htmlspecialchars($array[1]) . "</p>";
echo "<p><b>Date de fin</b> : " . htmlspecialchars($array[2]) . "</p>";

cardFooter();
?>