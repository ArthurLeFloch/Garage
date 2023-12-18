<?php

function clientExists($client_id)
{
    if (!is_numeric($client_id))
        return false;
    $request = "SELECT count(*) FROM clients WHERE client_id = $1;";
    $res = query($request, array($client_id));
    return pg_fetch_array($res)[0] == 1;
}
