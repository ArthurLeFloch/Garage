<?php

function manufacturerExists($manufacturer_id)
{
    if (!is_numeric($manufacturer_id))
        return false;
    $request = "SELECT count(*) FROM manufacturers WHERE manufacturer_id = $1;";
    $res = query($request, array($manufacturer_id));
    return pg_fetch_array($res)[0] == 1;
}
