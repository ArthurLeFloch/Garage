<!-- Required GET parameters: maintenance_id -->

<?php
include "../utils/parse.php";
include "../utils/query.php";
include "../utils/field_names.php";
include "../fragments/utils.php";
include "../fragments/table.php";

$maintenance_id = parseGet('maintenance_id');

$request = "SELECT client_id, vehicle_id FROM maintenances
            INNER JOIN vehicles USING (vehicle_id)
            INNER JOIN clients USING (client_id)
            WHERE maintenance_id = $1;";
$res = query($request, array($maintenance_id));
$array = pg_fetch_array($res);

clientVehicleCard($array[0], $array[1]);


cardHeader("Informations sur la maintenance");

$request = "SELECT STRING_AGG(intervention_name, ', '), 
                    maintenance_note, 
                    total_price, 
                    was_canceled, 
                    is_finished,
                    planned_start_date 
                    maintenance_start_date 
            FROM maintenances_view
            INNER JOIN maintenances_interventions USING (maintenance_id)
            INNER JOIN interventions USING (intervention_id)
            WHERE maintenance_id = $1
            GROUP BY maintenance_id, maintenance_note, total_price, was_canceled, is_finished, planned_start_date, maintenance_start_date
            ORDER BY planned_start_date DESC;";
$res = query($request, array($maintenance_id));
$array = pg_fetch_array($res);
if ($array[3] == "t") {
    echo "<div class='alert alert-danger' role='alert'>";
    echo "<b>La maintenance a été annulée !</b>";
    echo "</div>";
    echo "<p><b>Interventions prévues</b> : " . htmlspecialchars($array[0]) . "</p>";
} elseif ($array[4] == "t") {
    echo "<div class='alert alert-success' role='alert'>";
    echo "<b>Maintenance terminée !</b>";
    echo "</div>";
    echo "<p><b>Interventions réalisées</b> : " . htmlspecialchars($array[0]) . "</p>";
} elseif ($array['maintenance_start_date'] <= date('Y-m-d H:i:s')) {
    echo "<div class='alert alert-warning' role='alert'>";
    echo "<b>Maintenance en cours...</b>";
    echo "</div>";
    echo "<p><b>Interventions prévues</b> : " . htmlspecialchars($array[0]) . "</p>";
} else {
    echo "<div class='alert alert-primary' role='alert'>";
    echo "<b>Maintenance prévue pour le " . htmlspecialchars($array[5]) . "</b>";
    echo "</div>";
    echo "<p><b>Interventions prévues</b> : " . htmlspecialchars($array[0]) . "</p>";
}

echo "<p><b>Note</b> : " . htmlspecialchars($array[1]) . "</p>";
echo "<p><b>Montant facturé au client</b> : " . htmlspecialchars($array[2]) . "€</p>";

$request = "SELECT employee_first_name, employee_last_name FROM maintenances
            INNER JOIN employees_work_histories USING (maintenance_id)
            INNER JOIN employees USING (employee_id)
            WHERE maintenance_id = $1
            GROUP BY employee_first_name, employee_last_name;";
$res = query($request, array($maintenance_id));
$count = 0;
while ($array = pg_fetch_array($res)) {
    if ($count == 0) {
        echo "<p><b>Employés</b> : " . htmlspecialchars($array[0]) . " " . htmlspecialchars($array[1]);
    } else {
        echo ", " . htmlspecialchars($array[0]) . " " . htmlspecialchars($array[1]);
    }
    $count++;
}

echo "<hr>";

echo "<p><b>Sessions de travail</b></p>";

$request = "SELECT employee_id, employee_first_name, employee_last_name FROM employees
            ORDER BY employee_first_name, employee_last_name;";
$res = query($request);
if (pg_num_rows($res) == 0) {
    die("Aucun employé n'a été affecté à cette maintenance");
}

while ($employees = pg_fetch_array($res)) {
    $data = "SELECT
                MAKE_DATE(year_number, month_number, day_of_month) as work_date,
                start_hour::TIME as start_time,
                (start_hour + work_duration)::TIME as end_time
                FROM employees_work_histories
                INNER JOIN timeslots_ym USING (timeslot_ym_id)
                INNER JOIN timeslots_dh USING (timeslot_dh_id)
                INNER JOIN work_durations USING (work_duration_id)
                WHERE maintenance_id = $1 AND employee_id = $2;";
        
    $res2 = query($data, array($maintenance_id, $employees[0]));

    $table = new Table("", $employees[1] . " " . $employees[2]);
    $table->show($res2);

    echo "<br>";
}

echo "<hr>";

echo "<p><b>Pièces utilisées :</b></p>";

$request = "SELECT part_reference, part_name, unitary_price, number_of_parts, unitary_price * number_of_parts AS total_price FROM maintenances
            INNER JOIN maintenances_parts USING (maintenance_id)
            INNER JOIN parts USING (part_id)
            WHERE maintenance_id = $1
            ORDER BY part_reference ASC;";
$res = query($request, array($maintenance_id));

$table = new Table("parts-list");

$table->show($res);

cardFooter();
?>