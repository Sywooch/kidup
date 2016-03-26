<?php
namespace app\helpers;

class ApiHelper
{
    /**
     * Checks whether a response is api like, meaning it should be returned as json
     * @param $response
     * @return bool
     */
    public static function isApiLikeResponse($response)
    {
        if(is_string($response)){
            json_decode($response);
            return (json_last_error() == JSON_ERROR_NONE);
        }

        // all other formats are api like
        return true;
    }
}