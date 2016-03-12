<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 13.32
 */
class Checker
{

    public function isVariable($var, $empty = true)
    {
        if(isset($var) === false) return false;
        if($empty == false && empty($var)) return false;
        return true;
    }
    public function isArray($arr, $empty = true)
    {
        return $this->isVariable($arr, $empty) && is_array($arr);
    }

    public function isInt($int, $unsigned = false, $zero = true)
    {
        if($this->isVariable($int, $zero) === false)  return false;
        if(is_int($int) == false)                     return false;
        if($unsigned && $int < 0)                     return false;
        if($zero === false && $int === 0)             return false;
        return true;
    }

    public function isString($str, $empty = true)
    {
        return $this->isVariable($str, $empty) && is_string($str);
    }

    public function Contains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }

}