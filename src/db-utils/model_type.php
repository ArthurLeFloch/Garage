<?php

function modelTypeExists($model_type_id)
{
    $request = "SELECT count(*) FROM model_types WHERE model_type_id = $1;";
    $res = query($request, array($model_type_id));
    return pg_fetch_array($res)[0] == 1;
}
