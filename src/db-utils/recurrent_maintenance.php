<?php

function recurrentMaintenanceExists($recurrent_maintenance_id)
{
    if (!is_numeric($recurrent_maintenance_id))
        return false;
    $request = "SELECT count(*) FROM recurrent_maintenances WHERE recurrent_maintenance_id = $1;";
    $res = query($request, array($recurrent_maintenance_id));
    return pg_fetch_array($res)[0] == 1;
}
