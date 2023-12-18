<?php

function maintenanceExists($maintenance_id)
{
    if (!is_numeric($maintenance_id))
        return false;
    $request = "SELECT count(*) FROM maintenances WHERE maintenance_id = $1;";
    $res = query($request, array($maintenance_id));
    return pg_fetch_array($res)[0] == 1;
}

function get_maintenance_status($maintenance_id)
{
    if (!is_numeric($maintenance_id))
        return false;
    $request = "SELECT was_canceled, is_finished, (planned_start_date > NOW())
                FROM maintenances
                WHERE maintenance_id = $1;";
    $result = query($request, array($maintenance_id));
    $maintenance = pg_fetch_array($result);

    if ($maintenance[0] == "t")
        return "Annulé";
    elseif ($maintenance[1] == "t")
        return "Terminé";
    elseif ($maintenance[2] == "t")
        return "À venir";
    return "En cours";
}
