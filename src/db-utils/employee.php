<?php

function employeeExists($employee_id)
{
    if (!is_numeric($employee_id))
        return false;
    $request = "SELECT count(*) FROM employees WHERE employee_id = $1;";
    $res = query($request, array($employee_id));
    return pg_fetch_array($res)[0] == 1;
}
