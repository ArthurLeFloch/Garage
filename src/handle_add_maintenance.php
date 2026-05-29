<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/employee.php";
include "./db-utils/vehicle.php";
include "./db-utils/intervention.php";

$data = parseInput();

$vehicle_id = getValue($data, 'vehicle_id');
$planner_id = getValue($data, 'planner_id');
$start_date = getValue($data, 'start_date');
$duration = getValue($data, 'duration');
$total_price = getValue($data, 'total_price');
$mileage = getValue($data, 'mileage');
$intervention_ids = getValue($data, 'intervention_ids');
$status = getValue($data, 'status');
$notes = getValue($data, 'notes', false);

$employees_sessions = getValue($data, 'employees_sessions');

$is_finished = "FALSE";
$was_canceled = "FALSE";

if ($status == "done") {
    $is_finished = "TRUE";
} else if ($status == "canceled") {
    $was_canceled = "TRUE";
}

if (!vehicleExists($vehicle_id)) {
    die("Le véhicule renseigné n'existe pas.");
}

if (!employeeExists($planner_id)) {
    die("L'employé renseigné n'existe pas.");
}

foreach ($intervention_ids as $intervention_id) {
    if (!interventionExists($intervention_id)) {
        die("L'intervention renseignée n'existe pas.");
    }
}

function verifyEmployeeIsFree($employee_id, $work_date, $start_time, $end_time)
{
    $request = "SELECT employee_is_free($1, $2, $3, $4);";
    $result = query($request, array($employee_id, $work_date, $start_time, $end_time));

    if (pg_fetch_array($result)[0] == "f") {
        die("L'employé " . $employee_id . " est déjà occupé sur la période " . $start_time . " - " . $end_time);
    }
}

// Check if some employee_sessions overlap
foreach ($employees_sessions as $employee_sessions) {
    for ($i = 0; $i < count($employee_sessions['sessions']); $i++) {
        for ($j = $i + 1; $j < count($employee_sessions['sessions']); $j++) {
            $session1 = $employee_sessions['sessions'][$i];
            $session2 = $employee_sessions['sessions'][$j];

            if ($session1['work_date'] == $session2['work_date']) {
                if ($session1['start_time'] < $session2['end_time'] && $session1['end_time'] > $session2['start_time']) {
                    die("Deux sessions de travail se chevauchent pour l'employé " . $session1['employee_id'] . " le " . $session1['work_date']);
                }
            }
        }
    }
}

// Checking all employees and their work sessions before inserting anything
foreach ($employees_sessions as $employee_sessions) {
    $employee_id = $employee_sessions['employee_id'];
    if (!employeeExists($employee_id)) {
        die("L'employé " . $employee_id . " n'existe pas");
    }

    foreach ($employee_sessions['sessions'] as $session) {
        $work_date = $session['work_date'];
        $start_time = $session['start_time'];
        $end_time = $session['end_time'];

        if (trim($work_date) == "") {
            die("Une date de session de travail est vide");
        }
        if (trim($start_time) == "") {
            die("Une heure de début de session de travail est vide");
        }
        if (trim($end_time) == "") {
            die("Une heure de fin de session de travail est vide");
        }

        if (!isDate($work_date)) {
            die("La date de session de travail " . $work_date . " n'est pas valide");
        }

        verifyEmployeeIsFree($employee_id, $work_date, $start_time, $end_time);
    }
}


try {
    $duration = $duration * 3600;
    $planned_time_needed = $duration . " seconds";
    $request = "SELECT insert_maintenance ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10);";

    $result = query($request, array($vehicle_id, $planner_id, $start_date, $planned_time_needed, $was_canceled, $mileage, $total_price, $is_finished, $notes, $intervention_ids[0]));
} catch (Exception $e) {
    die("Une erreur est survenue lors de l'ajout de la maintenance. Le véhicule possède peut-être déjà une maintenance sur cette période.");
}

if (!$result) {
    die("Une erreur est survenue lors de l'ajout de la maintenance. Pas de retour de la base de données.");
}

$maintenance_id = pg_fetch_array($result)[0];

foreach ($employees_sessions as $employee_sessions) {
    $employee_id = $employee_sessions['employee_id'];
    foreach ($employee_sessions['sessions'] as $session) {
        $work_date = $session['work_date'];
        $start_time = $session['start_time'];
        $end_time = $session['end_time'];

        $request = "CALL insert_employee_work_history ($1, $2, $3, $4, $5);";
        query($request, array($maintenance_id, $employee_id, $work_date, $start_time, $end_time));
    }
}

foreach (array_slice($intervention_ids, 1) as $intervention_id) {
    $request = "CALL insert_maintenance_intervention ($1, $2);";

    query($request, array($maintenance_id, $intervention_id));
}
