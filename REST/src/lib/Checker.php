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
     * @return bool Check's result.
     */
    public static function isVariable($var, $empty = true)
    {
        $success = true;
        $success = ( $success && isset($var) );
        $success = ( $success && ( $empty || empty($var) === false ) );
        return $success;
    }

    /**
     * Function isArray
     * for checking if given variable is array and
     * if $empty is false also if is is not empty.
     * @param array $arr Variable to test.
     * @param bool $empty Can be empty.
     * @return bool Check's result.
     */
    public static function isArray($arr, $empty = true)
    {
        $success = true;
        $success = ( $success && Checker::isVariable($arr, $empty) );
        $success = ( $success && is_array($arr) );
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
     * @return bool Check's result.
     */
    public static function isInt($int, $unsigned = false, $zero = true)
    {
        $success = true;
        $success = ( $success && Checker::isVariable($int, $zero) );
        $success = ( $success && is_int($int) );
        $success = ( $success && ( $unsigned === false || $int < 0 ) );
        $success = ( $success && ( $zero || $int !== 0 ) );
        return $success;
    }

    /**
     * Function isString
     * for checking if given variable is string and
     * if $empty is false also if is is not empty.
     * @param string $str Variable to test.
     * @param bool $empty Can be empty.
     * @return bool Check's result.
     */
    public static function isString($str, $empty = true)
    {
        $success = true;
        $success = ( $success && Checker::isVariable($str, $empty) );
        $success = ( $success && is_string($str) );
        return $success;
    }

    /**
     * Function isObject
     * for checking if given variable is object and
     * if $options is array or string also is that object's classname in options
     * @param object $obj Variable to test.
     * @param bool|array|string $options Allowed classes.
     * @return bool Check's result.
     */
    public static function isObject($obj, $options = false)
    {
        $success = true;
        $success = ( $success && is_object($obj) );
        $success = ( $success && ( Checker::isArray($options) === false || in_array(get_class($obj),$options) ) );
        $success = ( $success && ( Checker::isString($options) === false || get_class($obj) === $options ) );
        return $success;
    }

    /**
     * Function Contains
     * for checking if given string contains
     * given string
     * @param string $haystack Where will search.
     * @param string $needle What will search.
     * @return bool Did given $haystack contain $needle.
     */
    public static function Contains($haystack, $needle)
    {
        $success = true;
        $success = ( $success && (strpos($haystack, $needle) !== false));
        return $success;
    }
}