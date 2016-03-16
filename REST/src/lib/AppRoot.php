<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.39
 */

/**
 * Class AppRoot
 * as root for App classes.
 */
class AppRoot extends Root
{
    /**
     * @var MySQL class for connecting to database.
     */
    private $mysql;

    /**
     * Function MySQL
     * for getting object's MySQL class.
     * @return MySQL class for connecting to database.
     */
    private function MySQL()
    {
        return $this->mysql;
    }

    /**
     * Function setMySQL
     * for setting object's MySQL class.
     * @param $mysql MySQL to set as object's MySQL class.
     * @return bool Success of the function.
     */
    private function setMySQL($mysql)
    {
        $success = false;
        if($this->isObject($mysql, "MySQL"))
        {
            $this->mysql = $mysql;
            $success = true;
        }
        return $success;
    }

    /**
     * @var AUTHMySQL Authorisation data.
     */
    private $authMysql;

    /**
     * Function AuthMySQL
     * for
     * @return AUTHMySQL MySQL Authorisation data.
     */
    private function AuthMySQL()
    {
        return $this->authMysql;
    }

    protected function __construct()
    {
        parent::__construct();
    }
}