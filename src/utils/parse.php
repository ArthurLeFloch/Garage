<?php

function parseInput()
{
    $tmp = file_get_contents("php://input");
    $json = json_decode($tmp, true);
    return $json;
}

function getValue($json, $name, $required = true)
{
    global $fields;
    include_once __DIR__ . "/field_names.php";

    if (!isset($json[$name]) && $required) {
        die("Le champ $name n'a pas été rempli !");
    }

    if (is_string($json[$name])) {
        $result = trim($json[$name]);
        if ($result === "" && $required) {
            die("Le champ " . convert($name) . " n'a pas été rempli !");
        }
        return $result;
    } elseif (is_array($json[$name]) || is_int($json[$name])) {
        return $json[$name];
    } else {
        throw new Exception("Argument $name has an invalid type");
    }
}

function parseGet($name, $required = true)
{
    $result = isset($_GET[$name]) ? trim($_GET[$name]) : '';
    if (empty($result) && $required) {
        die("Le champ $name n'a pas été rempli !");
    }
    return $result;
}

function isEmail($string)
{
    return filter_var($string, FILTER_VALIDATE_EMAIL) !== false;
}

function isPhoneNumber($string)
{
    return preg_match("/^(\+\d+)?[0-9\s]+$/", $string);
}

function isDate($dateString, $format = 'Y-m-d')
{
    $dateTimeObj = DateTime::createFromFormat($format, $dateString);
    return $dateTimeObj && $dateTimeObj->format($format) === $dateString;
}

function isDateBefore($dateString1, $dateString2, $format = 'Y-m-d')
{
    $dateTime1 = DateTime::createFromFormat($format, $dateString1);
    $dateTime2 = DateTime::createFromFormat($format, $dateString2);

    if ($dateTime1 && $dateTime2) {
        return $dateTime1 <= $dateTime2;
    }

    return false;
}
