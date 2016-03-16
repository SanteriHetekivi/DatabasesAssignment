<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 12.22
 */

/**
 * Class MySQL
 * for managing connections for MySQL server.
 */
class MySQL extends Root
{
    /**
     * @var PDO for connecting to database.
     */
    private $conn;

    /**
     * Function Conn
     * for getting PDO connection object.
     * @return PDO of connection
     */
    private function Conn()
    {
        return $this->conn;
    }

    /**
     * Function setConn
     * for setting PDO connection.
     * @param PDO $conn connection object to set as connection.
     * @return bool Success of the function.
     */
    private function setConn($conn)
    {
        $success = false;
        if($this->isObject($conn, "PDO", __FUNCTION__))
        {
            $this->conn = $conn;
            $success = true;
        }
        return $success;
    }

    /**
     * MySQL constructor.
     * @param string $address Address of the database.
     * @param string $database Name of the database.
     * @param string $username Username for the connection.
     * @param string $password Password for the connection.
     */
    public function __construct($address, $database, $username, $password)
    {
        parent::__construct();
        $this->CONNECT($address, $database, $username, $password);
    }

    /**
     * MySQL destructor.
     */
    public function __destruct()
    {
        $this->DISCONNECT();
        unset($this->conn);
        unset($this->cleaner);
        unset($this->query);
        parent::__destruct();
    }

    /**
     * Function CONNECT
     * for connecting to database.
     * @param string $address Address of the database.
     * @param string $database Name of the database.
     * @param string $username Username for the connection.
     * @param string $password Password for the connection.
     * @return bool Success of the function.
     */
    private function CONNECT($address, $database, $username, $password)
    {
        $success = false;
        //If all variables are set
        if(Checker::isString($address, true) && Checker::isString($database, true) &&
            Checker::isString($username, true) && Checker::isString($password))
        {
            try
            {
                $conn = new PDO("mysql:host=".$address.";dbname=".$database.";charset=utf8", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $success = $this->setConn($conn);
            }
            catch(PDOException $e)
            {
                $this->addError(__FUNCTION__, "Connection to data space failed!", $e->getMessage());
                $conn = null;
            }
        }
        else
        {
            $this->addError(__FUNCTION__, "Passed values are not set!", array($address, $database,$username, $password));
        }

        return $success;
    }

    /**
     * Function DISCONNECT
     * for disconnecting from database.
     * @return bool Success of the function.
     */
    private function DISCONNECT()
    {
        $success = false;
        //If connection is set
        if($this->checkConnection(__FUNCTION__)) {
            $this->conn = null;
            $success = true;
        }

        return $success;
    }

    /**
     * Function checkConnection
     * for checking connection to database.
     * @param bool|string $addErrorFunction Function name if error is wanted.
     * @return bool Was there connection.
     */
    private function checkConnection($addErrorFunction = false)
    {
        $success = Checker::isObject($this->Conn(), "PDO");
        if($addErrorFunction) $this->addError($addErrorFunction, "Connection is not set!", $this->Conn());
        return $success;
    }

    /**
     * Function CALL
     * for sending MySQL queries to database.
     * @param string $sql MySQL query.
     * @return array|bool Result of the query, or false if there was exeptions.
     */
    private function CALL($sql)
    {
        $result = false;

        if(Checker::isString($sql, true))
        {
            if($this->checkConnection(__FUNCTION__))
            {
                try
                {
                    $stmt = $this->Conn()->prepare($sql);
                    $result = $stmt->execute();
                    if(Checker::Contains($sql, "SELECT")) $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e)
                {
                    $result = false;
                    $this->addError(__FUNCTION__, "SQL command failed.", $e->getMessage());
                }
            }
        }

        return $result;
    }

    /**
     * Function SELECT
     * for making SELECT query to database.
     * @param string|array $columns Columns for the query.
     * @param string $table Table for the query.
     * @return bool|string|array Response from the database if successful false if not.
     */
    public function SELECT($columns = "*", $table)
    {
        $return = false;
        $sql = MySQLParser::Select($columns, $table);
        if($sql)
        {
            $return = $this->CALL($sql);
        }
        return $return;
    }
}
