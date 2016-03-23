<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 22.3.2016
 * Time: 13.07
 */
class Parser
{
    public static function Int($int, $errorInfo = false)
    {
        $return = false;
        if(Checker::isNumeric($int, $errorInfo))
        {
            $return = (int)$int;
        }
        return $return;
    }
}