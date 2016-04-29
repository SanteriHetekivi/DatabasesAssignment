<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 14.4.2016
 * Time: 14.42
 */

/**
 * Class Tools
 * for storing static tools for apps.
 */
class Tools
{
    /**
     * Function DATETIME
     * for getting MySQL DATETIME.
     * @return bool|string Date time in MySQL friendly format or false on failure.
     */
    public static function DATETIME(){
        return date('Y-m-d H:i:s');
    }

    /**
     * Function TimeNow
     * for getting current unix timestamp.
     * @return int Time in seconds from Unix Epoch.
     */
    public static function TimeNow(){
        return time();
    }

    public static function TimeIsInside($start, $end, $cStart, $cEnd)
    {
        return ($start > $cEnd && $end > $cStart);
    }

}