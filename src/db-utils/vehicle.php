<?php

function vehicleExists($vehicle_id)
{
    if (!is_numeric($vehicle_id))
        return false;
    $request = "SELECT count(*) FROM vehicles WHERE vehicle_id = $1;";
    $res = query($request, array($vehicle_id));
    return pg_fetch_array($res)[0] == 1;
}
