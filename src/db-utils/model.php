<?php

function modelExists($model_id)
{
    if (!is_numeric($model_id))
        return false;
    $request = "SELECT count(*) FROM models WHERE model_id = $1;";
    $res = query($request, array($model_id));
    return pg_fetch_array($res)[0] == 1;
}
