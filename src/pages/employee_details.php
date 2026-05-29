<!-- Required GET parameters: employee_id -->

<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/table.php";
include "../fragments/utils.php";
require_once "../utils/field_names.php";

$employee_id = parseGet('employee_id');

employeeCard($employee_id);

cardHeader("Sessions de travail");

$request = "SELECT
            MAKE_DATE(year_number, month_number, day_of_month) as work_date,
            start_hour::TIME as start_time,
            (start_hour + work_duration)::TIME as end_time
            FROM employees_work_histories
            INNER JOIN timeslots_ym USING (timeslot_ym_id)
            INNER JOIN timeslots_dh USING (timeslot_dh_id)
            INNER JOIN work_durations USING (work_duration_id)
            WHERE employee_id = $1
            ORDER BY work_date DESC, start_time DESC;";
$res = query($request, array($employee_id));

$table = new Table("session-list");

$table->show($res);

cardFooter();
?>