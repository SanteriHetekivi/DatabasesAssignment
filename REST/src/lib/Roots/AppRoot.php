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
class AppRoot extends MySQLRoot
{
    /**
     * AppRoot constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        $this->FILE = __FILE__;
    }

    public function GET($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        if(isset($pars["par0"]))
        {
            $object = $pars["par0"];
            if(Checker::isString($object, false, $errorInfo)) {
                if (class_exists($object)) {
                    if (isset($pars["par1"])) {
                        $id = Parser::Int($pars["par1"], $errorInfo);
                        if (MySQLChecker::isId($id, $errorInfo)) {
                            $obj = new $object($id);
                            $return = $obj->Values();
                        }
                    } else {
                        $obj = new $object();
                        $return = $obj->GetAllValues();
                    }
                } else $this->addError(__FUNCTION__, "No class with given name!", $object);
            }
        }
        DATA::setSuccess((Checker::isArray($return)));
        return $return;
    }


    public function CALL($action, $pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        if(Checker::isString($action, false, $errorInfo) &&  method_exists($this, $action) && is_callable(array($this, $action)))
        {
            $return = $this->$action($pars);
        }
        return $return;
    }



}
