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
        if($this->isObject($conn, "PDO", $this->ERROR_INFO(__FUNCTION__)))
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
        $this->FILE = __FILE__;
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
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        //If all variables are set
        if(Checker::isString($address, true, $errorInfo) && Checker::isString($database, true, $errorInfo) &&
            Checker::isString($username, true, $errorInfo) && Checker::isString($password, true, $errorInfo))
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
    public function checkConnection($addErrorFunction = false)
    {
        $success = Checker::isObject($this->Conn(), "PDO");
        if($addErrorFunction && $success === false) $this->addError($addErrorFunction,
            "Connection is not set!", $this->Conn());
        return $success;
    }

    /**
     * Function CALL
     * for sending MySQL queries to database.
     * @param MySQLQuery $query MySQLQuery to run.
     * @param bool $onlyOne Return only one row.
     * @return array|bool Result of the query, or false if there was exceptions.
     */
    public function CALL($query, $onlyOne = false)
    {
        $result = false;
        if($this->isObject($query, "MySQLQuery", __FUNCTION__))
        {
            $data = $query->Query();
            $errorInfo = $this->ERROR_INFO(__FUNCTION__);
            if(Checker::isArray($data, false, $errorInfo) &&
                Checker::isString($data[Where::QUERY], false, $errorInfo) &&
                Checker::isArray($data[Where::VALUES], true, $errorInfo)) {
                $values = $data[Where::VALUES];
                $sql = $data[Where::QUERY];
                //d(array("query" => $sql, "Values" => $values));
                if ($this->checkConnection(__FUNCTION__)) {
                    try {
                        $stmt = $this->Conn()->prepare($sql);
                        if (Checker::isArray($values, false)) {
                            foreach ($values as $key => &$value) {
                                $stmt->bindParam($key, $value);
                            }
                        }
                        $result = $stmt->execute();
                        if ($query->Action() === "SELECT"){
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if($onlyOne && Checker::isArray($result,false, $errorInfo)) $result = current($result);
                        }
                        elseif($query->Action() === "INSERT")
                        {
                            $result = $this->Conn()->lastInsertId();
                            var_dump($result);
                        }

                    } catch (PDOException $e) {
                        $result = false;
                        $this->addError(__FUNCTION__, "SQL command failed.", $e->getMessage());
                    }
                }
            }
        }

        return $result;
    }
}
