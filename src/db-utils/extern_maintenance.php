<?php

function externMaintenanceExists($extern_maintenance_id)
{
    if (!is_numeric($extern_maintenance_id))
        return false;
    $request = "SELECT count(*) FROM extern_maintenances WHERE extern_maintenance_id = $1;";
    $res = query($request, array($extern_maintenance_id));
    return pg_fetch_array($res)[0] == 1;
}
