<?php

$connection_string = "host=localhost port=5432 dbname=garage user=web password=password";
$connection = pg_connect($connection_string);
$connection;
if (!$connection) {
    echo pg_last_error();
}

?>
