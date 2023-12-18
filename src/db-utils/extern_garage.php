<?php

function externGarageNameExists($extern_garage_name)
{
    $request = "SELECT count(*) FROM extern_garages WHERE extern_garage_name = $1;";
    $res = query($request, array($extern_garage_name));
    return pg_fetch_array($res)[0] == 1;
}
