<?php

function interventionExists($intervention_id)
{
    if (!is_numeric($intervention_id))
        return false;
    $request = "SELECT count(*) FROM interventions WHERE intervention_id = $1;";
    $res = query($request, array($intervention_id));
    return pg_fetch_array($res)[0] == 1;
}
