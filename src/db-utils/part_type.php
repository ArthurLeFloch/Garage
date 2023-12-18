<?php

function partTypeExists($part_type_id)
{
    if (!is_numeric($part_type_id))
        return false;
    $request = "SELECT count(*) FROM part_types WHERE part_type_id = $1;";
    $res = query($request, array($part_type_id));
    return pg_fetch_array($res)[0] == 1;
}
