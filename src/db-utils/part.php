<?php

function partExists($part_id)
{
    if (!is_numeric($part_id))
        return false;
    $request = "SELECT count(*) FROM parts WHERE part_id = $1;";
    $res = query($request, array($part_id));
    return pg_fetch_array($res)[0] == 1;
}
