<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 13.32
 */

/**
 * Class Checker
 * for checking variables.
 */
class Checker
{
    /**
     * Function isVariable
     * for checking if given variable is set and
     * if $empty is false also if is is not empty.
     * @param object|string|int|bool|float $var Variable to test.
     * @param bool $empty Can be empty.
     * @param bool|array $errors Add errors.
     * @return bool Check's result.
     */
    public static function isVariable($var, $empty = true, $errors = false)
    {
        $success = true;
        $success = ($success && isset($var));
        $success = ($success && ($empty || empty($var) === false));
        if ($success === false && $errors) ErrorCollection::addErrorInfo($errors,
            "Given var is not variable or empty is not allowed and it is empty", $var);
        return $success;
    }

    /**
     * Function isArray
     * for checking if given variable is array and
     * if $empty is false also if is is not empty.
     * @param array $arr Variable to test.
     * @param bool $empty Can be empty.
     * @param bool|array $errors Add errors.
     * @return bool Check's result.
     */
    public static function isArray($arr, $empty = true, $errors = false)
    {
        $success = true;
        $success = ($success && Checker::isVariable($arr, $empty));
        $success = ($success && is_array($arr));
        if ($success === false && $errors) ErrorCollection::addErrorInfo($errors,
            "Given arr is not array or empty is not allowed and it is empty", $arr);
        return $success;
    }

    /**
     * Function isInt
     * for checking if given variable is int and
     * if $unsigned is true also if it is unsigned and
     * if $zero is false if it is not zero.
     * @param int $int Variable to test.
     * @param bool $unsigned Must be unsigned.
     * @param bool $zero Can be zero.
     * @param bool|array $errors Add errors.
     * @return bool Check's result.
     */
    public static function isInt($int, $unsigned = false, $zero = true, $errors = false)
    {
        $success = true;
        $success = ($success && Checker::isVariable($int, $zero));
        $success = ($success && is_int($int));
        $success = ($success && ($unsigned === false || $int >= 0));
        $success = ($success && ($zero || $int !== 0));
        if ($success === false && $errors) ErrorCollection::addErrorInfo($errors,
            "Given int is not int or breaks given settings!", $int);
        return $success;
    }

    /**
     * Function isString
     * for checking if given variable is string and
     * if $empty is false also if is is not empty.
     * @param string $str Variable to test.
     * @param bool $empty Can be empty.
     * @param bool|array $errors Add errors.
     * @return bool Check's result.
     */
    public static function isString($str, $empty = true, $errors = false)
    {
        $success = true;
        $success = ($success && Checker::isVariable($str, $empty));
        $success = ($success && is_string($str));
        if ($success === false && $errors) ErrorCollection::addErrorInfo($errors,
            "Given string is not string or empty is not allowed and it is empty", $str);
        return $success;
    }

    /**
     * Function isObject
     * for checking if given variable is object and
     * if $options is array or string also is that object's classname in options
     * @param object $obj Variable to test.
     * @param bool|array|string $options Allowed classes.
     * @param bool|array $errors Add errors.
     * @return bool Check's result.
     */
    public static function isObject($obj, $options = false, $errors = false)
    {
        $success = true;
        $success = ($success && is_object($obj));
        $success = ($success && (Checker::isArray($options, false, false) === false ||
                in_array(get_class($obj), $options)));
        $success = ($success && (Checker::isString($options, false, false) === false ||
                get_class($obj) === $options));
        if ($success === false && $errors) ErrorCollection::addErrorInfo($errors,
            "Given obj is not object or object of right type!", array("object" => $obj, "options" => $options));
        return $success;
    }

    /**
     * Function isBool
     * for checking if given variable is boolean.
     * @param bool $bool Variable to test.
     * @param bool|array $errors Add errors.
     * @return bool Check's result.
     */
    public static function isBool($bool, $errors = false)
    {
        $success = true;
        $success = ($success && is_bool($bool));
        if ($success === false && $errors) ErrorCollection::addErrorInfo($errors, "Given values is not bool!", $bool);
        return $success;
    }

    /**
     * Function Contains
     * for checking if given string contains
     * given string
     * @param string $haystack Where will search.
     * @param string $needle What will search.
     * @param bool|array $errors Add errors.
     * @return bool Did given $haystack contain $needle.
     */
    public static function Contains($haystack, $needle, $errors = false)
    {
        $success = true;
        $success = ($success && (strpos($haystack, $needle) !== false));
        if ($success === false && $errors) ErrorCollection::addErrorInfo($errors, "Given needle is not in haystack!",
            array("haystack" => $haystack, "needle" => $needle));
        return $success;
    }

    /**
     * Function isNumeric
     * for checking if given number is numeric.
     * @param string $number Number to check.
     * @param bool|array $errors Add errors.
     * @return bool Was given keyword supported.
     */
    public static function isNumeric($number, $errors = false)
    {
        $success = is_numeric($number);
        if($success ===  false && $errors) ErrorCollection::addErrorInfo($errors,"Given number is not numeric!",
            $number);
        return $success;
    }

}
