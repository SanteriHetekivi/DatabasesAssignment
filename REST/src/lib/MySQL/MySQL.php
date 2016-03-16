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
     * @var MySQLParser for parsing MySQL commands.
     */
    private $parser;
    /**
     * Function Parser
     * for getting objects MySQLParser
     * @return MySQLParser if it is set.
     */
    private function Parser()
    {
        return $this->parser;
    }

    /**
     * Function setParser
     * for setting MySQLParser object to class.
     * @param MySQLParser $parser to set as parser.
     * @return bool Success of the function.
     */
    private function setParser($parser)
    {
        $success = false;
        if(Checker::isObject($parser, "MySQLParser"))
        {
            $this->parser = $parser;
            $success = true;
        }
        else
        {
            $this->addError(__FUNCTION__, "Passed parser is not MySQLParser object!", $parser);
        }
        return $success;
    }

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
        if(Checker::isObject($conn, "PDO"))
        {
            $this->conn = $conn;
            $success = true;
        }
        else
        {
            $this->addError(__FUNCTION__, "Passed conn is not PDO object!", $conn);
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
        $this->setParser(new MySQLParser($this->Conn()));
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
}
