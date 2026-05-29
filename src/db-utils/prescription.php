<?php

function prescriptionExists($prescription_id)
{
    if (!is_numeric($prescription_id))
        return false;
    $request = "SELECT count(*) FROM prescriptions WHERE prescription_id = $1;";
    $res = query($request, array($prescription_id));
    return pg_fetch_array($res)[0] == 1;
}
