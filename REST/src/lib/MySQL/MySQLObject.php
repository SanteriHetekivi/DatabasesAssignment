<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.28
 */

/**
 * Class MySQLObject
 * for setting basic root of
 * MySQLObject.
 */
class MySQLObject extends Root
{
    /**
     * MySQLObject constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        $this->FILE = __FILE__;
    }

    /**
     * MySQLObject destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }
}