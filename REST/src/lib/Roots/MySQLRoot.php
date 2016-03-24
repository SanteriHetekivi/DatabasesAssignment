<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 22.3.2016
 * Time: 12.38
 */

/**
 * Class MySQLRoot
 * for all classes needing MySQL connection.
 */
class MySQLRoot extends Root
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
        if($this->isObject($mysql, "MySQL", false, __FUNCTION__))
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

    /**
     * Function Connected
     * for checking if object
     * is connected to MySQL database.
     * @param bool|string $errorFunction Error's function name. (Optional)
     * @return bool Is object connected to MySQL database.
     */
    public function Connected($errorFunction = false)
    {
        return $this->isObject($this->MySQL(), "MySQL", false, $errorFunction) && $this->MySQL()->checkConnection($errorFunction);
    }

    /**
     * MySQLRoot constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        $this->FILE = __FILE__;
        $this->Connect();
    }
}