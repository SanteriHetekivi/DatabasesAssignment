<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 18.3.2016
 * Time: 11.21
 */

/**
 * Class DATA
 * for making return data.
 */
class DATA
{
    /**
     * @var bool Was operation successful.
     */
    private static $success = false;

    /**
     * Function Success
     * for getting info about operators success.
     * @return bool Was operation successful.
     */
    public static function Success()
    {
        return DATA::$success;
    }

    /**
     * Function setSuccess
     * for setting operators success.
     * @param bool $_success Operators success.
     * @return bool Success of the function.
     */
    public static function setSuccess($_success)
    {
        $success = false;
        if(Checker::isBool($_success))
        {
            DATA::$success = $_success;
            $success = true;
        }
        return $success;
    }

    /**
     * Function MAKE
     * for making return data.
     * @param array|bool|string $data Data that will be set as return data's data.
     * @return array with system values and data.
     */
    public static function MAKE($data)
    {
        $return = array(
            "system" => array(
                "errors" => ErrorCollection::ErrorsData(),
                "success"=> DATA::Success(),
            ),
            "client" => array(
                "data" => $data,
                "messages" => MessageCollection::MessagesData(),
            )
        );
        return $return;
    }

}
