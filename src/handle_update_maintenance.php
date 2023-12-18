<?php
include "./utils/parse.php";
include "./utils/query.php";
include "./db-utils/employee.php";
include "./db-utils/maintenance.php";

$data = parseInput();

$maintenance_id = getValue($data, 'maintenance_id');
$planner_id = getValue($data, 'planner_id');
$start_date = getValue($data, 'start_date');
$duration = getValue($data, 'duration');
$total_price = getValue($data, 'total_price');
$mileage = getValue($data, 'mileage');
$intervention_ids = getValue($data, 'intervention_ids');
$status = getValue($data, 'status');
$notes = getValue($data, 'notes', false);

$employees_sessions = getValue($data, 'employees_sessions');

if (!employeeExists($planner_id)) {
    die("L'employé renseigné n'existe pas.");
}

function verifyEmployeeIsFree($maintenance_id, $employee_id, $work_date, $start_time, $end_time)
{
    $request = "SELECT employee_is_free($1, $2, $3, $4, $5);";
    $result = query($request, array($maintenance_id, $employee_id, $work_date, $start_time, $end_time));

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

        verifyEmployeeIsFree($maintenance_id, $employee_id, $work_date, $start_time, $end_time);
    }
}


$employees_ids = array();
foreach ($employees_sessions as $employee_sessions) {
    array_push($employees_ids, $employee_sessions['employee_id']);
}

// Remove all employees not in the new list from employees_work_histories
$request = "DELETE FROM employees_work_histories WHERE maintenance_id = $1 AND employee_id NOT IN (";
for ($i = 0; $i < count($employees_ids); $i++) {
    $request .= "$" . ($i + 2);
    if ($i != count($employees_ids) - 1) {
        $request .= ", ";
    }
}
$request .= ");";
query($request, array_merge(array($maintenance_id), $employees_ids));

$is_finished = "FALSE";
$was_canceled = "FALSE";

if ($status == "done") {
    $is_finished = "TRUE";
} else if ($status == "canceled") {
    $was_canceled = "TRUE";
}

if (!maintenanceExists($maintenance_id)) {
    die("La maintenance renseignée n'existe pas.");
}

if (!employeeExists($planner_id)) {
    die("L'employé renseigné n'existe pas.");
}


try {
    $duration = $duration * 3600;
    $planned_time_needed = $duration . " seconds";

    // check if has work history

    $request = "SELECT COUNT(*) 
    FROM employees_work_histories 
    WHERE maintenance_id = $1;";
    $result = query($request, array($maintenance_id));
    $count = pg_fetch_array($result)[0];
    if ($count <= 0 && $is_finished == "TRUE") {
        die ("La maintenance n'a pas d'histoires de travail, mettez la comme annulée si vous voulez vous en débarasser.");
    } else {
        $request = "UPDATE maintenances SET planner_id = $2, planned_start_date = $3, planned_time_needed = $4, mileage_vehicle = $5, total_price = $6, is_finished = $7, was_canceled = $8, maintenance_note = $9
                WHERE maintenance_id = $1;";
        query($request, array($maintenance_id, $planner_id, $start_date, $planned_time_needed, $mileage, $total_price, $is_finished, $was_canceled, $notes));
    }
} catch (Exception $e) {
    die("Une erreur est survenue lors de la modification de la maintenance. Le véhicule possède peut-être déjà une maintenance sur cette période.");
}

// Delete first, re-insert after
foreach ($employees_sessions as $employee_sessions) {
    $employee_id = $employee_sessions['employee_id'];
    $request = "DELETE FROM employees_work_histories WHERE maintenance_id = $1 AND employee_id = $2;";
    query($request, array($maintenance_id, $employee_id));
    foreach ($employee_sessions['sessions'] as $session) {
        $work_date = $session['work_date'];
        $start_time = $session['start_time'];
        $end_time = $session['end_time'];

        $request = "CALL insert_employee_work_history ($1, $2, $3, $4, $5);";
        query($request, array($maintenance_id, $employee_id, $work_date, $start_time, $end_time));
    }
}


$request = "DELETE FROM maintenances_interventions WHERE maintenance_id = $1 AND intervention_id NOT IN (";
for ($i = 0; $i < count($intervention_ids); $i++) {
    $request .= "$" . ($i + 2);
    if ($i != count($intervention_ids) - 1) {
        $request .= ", ";
    }
}
$request .= ");";
query($request, array_merge(array($maintenance_id), $intervention_ids));


$request = "INSERT INTO maintenances_interventions (maintenance_id, intervention_id)
            SELECT $1, intervention_id FROM interventions
            WHERE intervention_id IN (";
for ($i = 0; $i < count($intervention_ids); $i++) {
    $request .= "$" . ($i + 2);
    if ($i != count($intervention_ids) - 1) {
        $request .= ", ";
    }
}
$request .= ") AND intervention_id NOT IN (SELECT intervention_id FROM maintenances_interventions WHERE maintenance_id = $1);";
query($request, array_merge(array($maintenance_id), $intervention_ids));
