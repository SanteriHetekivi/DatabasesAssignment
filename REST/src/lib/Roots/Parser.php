<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 22.3.2016
 * Time: 13.07
 */

/**
 * Class Parser
 * for parsing values.
 */
class Parser
{
    /**
     * Function Int
     * for making int from given value.
     * @param string|int $int Value to make to int.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return int|null Int if successful else null.
     */
    public static function Int($int, $errorInfo = false)
    {
        $return = null;
        if(Checker::isNumeric($int, $errorInfo))
        {
            $return = (int)$int;
        }
        return $return;
    }

    /**
     * Function Bool
     * for making bool from given value.
     * @param string|int|bool|double|float $bool Value to make to bool.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Bool from given value.
     */
    public static function Bool($bool, $errorInfo = false)
    {
        return (bool)$bool;
    }

    /**
     * Function String
     * for making string from given value.
     * @param string|int|bool|double|float $str Value to make to string.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return string String of given value.
     */
    public static function String($str, $errorInfo = false)
    {
        return (string)$str;
    }

    /**
     * Function DATETIME
     * for making unix timestamp to MySQL DATETIME.
     * @param int $time Unix timestamp.
     * @return bool|string Date time in MySQL friendly format or false on failure.
     */
    public static function DATETIME($time)
    {
        return date("Y-m-d H:i:s",$time);
    }

    /**
     * Function Time
     * for making date string to unix timestamp.
     * @param string $date Date string to convert.
     * @return int Unix timestamp representing string.
     */
    public static function Time($date)
    {
        return strtotime($date);
    }

    /**
     * Function
     * @param int|MySQLObject $id_object ID or MySQLObject to parse to MySQLObject.
     * @param string $objectName Name of MySQLObject.
     * @param bool $select Does parser do SELECT. (Optional)
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool|MySQLObject Parsed MySQLObject or false if failed.
     */
    public static function MySQLObject($id_object, $objectName, $select = false, $errorInfo = false)
    {
        if(Checker::isString($objectName, false, $errorInfo))
        {
            if (!Checker::isObject($id_object))
            {
                if (MySQLChecker::isId($id_object, $errorInfo)) $object = new $objectName($id_object);
                else return false;
            } else $object = $id_object;
            if (Checker::isObject($object, $objectName, "MySQLObject", $errorInfo)) {
                /** @var MySQLObject $object * */
                if ($select) {
                    if ($object->SELECT()) return $object;
                    else {
                        if ($errorInfo) ErrorCollection::addErrorInfo($errorInfo, "SELECT failed!", $object);
                        return false;
                    }
                } else return $object;
            }else return false;
        }
        return false;
    }

}