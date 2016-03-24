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

}