<?php

require_once('../utils/parse.php');
require_once('../utils/query.php');
require_once('../fragments/card.php');
require_once('../fragments/client.php');
require_once('../fragments/vehicle.php');
require_once('../fragments/employee.php');

function clientCard($client_id)
{
    echo "<div class='row g-3 mb-3'>";
    echo "<div class='col-12'>";
    clientFragment($client_id);
    echo "</div>";
    echo "</div>";
}

function employeeCard($employee_id)
{
    echo "<div class='row g-3 mb-3'>";
    echo "<div class='col-12'>";
    employeeFragment($employee_id);
    echo "</div>";
    echo "</div>";
}

function clientVehicleCard($client_id, $vehicle_id)
{
    echo "<div class='row g-3 mb-3'>";

    echo "<div class='col-md-6'>";
    clientFragment($client_id);
    echo "</div>";

    echo "<div class='col-md-6'>";
    vehicleFragment($vehicle_id);
    echo "</div>";

    echo "</div>";
}
