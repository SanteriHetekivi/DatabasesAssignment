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
    protected function MySQL()
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
     * Function Connect
     * for connecting app to MySQL database.
     * @return bool Was connection successful.
     */
    protected function Connect()
    {
        $success = false;
        if(class_exists("AUTHMySQL"))
        {
            $success = $this->setMySQL(
                new MySQL(AUTHMySQL::$MYSQL_ADDRESS, AUTHMySQL::$MYSQL_DATABASE,
                    AUTHMySQL::$MYSQL_USERNAME, AUTHMySQL::$MYSQL_PASSWORD)
            );
        }
        else
        {
            $this->addError(__FUNCTION__, "No class named AUTHMySQL!", "");
        }
        return $success;
    }

    protected function __construct()
    {
        parent::__construct();
        $this->Connect();
    }
}