<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.40
 */

/**
 * Class App
 * for Databases
 */
class App extends AppRoot
{
    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->FILE = __FILE__;
    }

    /**
     * Function TEST
     * for testing.
     * @return array|bool|string test result.
     */
    public function TEST()
    {

    }

    public function works_on($pars)
    {
        $return = array();
        if(isset($pars["par0"]))
        {

            $errorInfo = $this->ERROR_INFO(__FUNCTION__);
            $id = Parser::Int($pars["par0"], $errorInfo);
            if(MySQLChecker::isId($id, $errorInfo))
            {
                $tekee = new Tekee();
                $return = $tekee->getEmployees($id);
                d($return);
            }
        }
        DATA::setSuccess((Checker::isArray($return)));
        return $return;
    }
}