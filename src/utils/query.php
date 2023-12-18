<?php

// $parameters is an array, where elements are meant to
// replace $1, $2, ... in $request.

// This function returns a "PgSQL result", or a boolean if nothing is returned.
function query($request, $parameters = array())
{
    global $connection;
    include_once __DIR__ . "/connect_pg.php";

    foreach ($parameters as &$param) {
        $param = pg_escape_string($connection, $param);
    }

    $result = pg_query_params($connection, $request, $parameters);

    if (!$result) {
        die(pg_last_error($connection) . "Prepared request: " . $request);
    }

    return $result;
}

?>