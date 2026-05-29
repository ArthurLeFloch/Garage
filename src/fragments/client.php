<?php

require_once('../utils/parse.php');
require_once('../utils/query.php');
require_once('../fragments/card.php');

function clientFragment($client_id)
{
    $request = "SELECT client_first_name, client_last_name, client_address, client_email, client_mobile FROM clients WHERE client_id = $1";
    $res = query($request, array($client_id));
    $array = pg_fetch_array($res);
    $surname = htmlspecialchars($array[0]);
    $name = htmlspecialchars($array[1]);
    $address = htmlspecialchars($array[2]);
    $email = htmlspecialchars($array[3]);
    $mobile = htmlspecialchars($array[4]);

    cardHeader("Client : $surname $name", false);

    echo "<p class='card-text'><b>Adresse</b> : $address</p>";
    echo "<p class='card-text'><b>Email</b> : $email</p>";
    echo "<p class='card-text'><b>Mobile</b> : $mobile</p>";

    cardFooter();
}
