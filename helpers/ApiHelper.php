<?php
namespace app\helpers;

class ApiHelper
{
    public static function isJSON($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}