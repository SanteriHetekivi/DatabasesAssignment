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

    /**
     * Function GET
     * for getting values.
     * @param array $pars Key: par0 = Object name, Key: par1 Id of object (Optional)
     * @return array Values for given column and id if set.
     */
    public function GET($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        if(isset($pars["par0"]))
        {
            $object = $pars["par0"];
            if(Checker::isString($object, false, $errorInfo)) {
                if (class_exists($object)) {
                    $obj = new $object();
                    if(Checker::isObject($obj, false, "MySQLObject", $errorInfo))
                    {
                        if (isset($pars["par1"])) {
                            $id = Parser::Int($pars["par1"], $errorInfo);
                            if (MySQLChecker::isId($id, $errorInfo)) {
                                $obj->setID($id);
                                $obj->SELECT();
                                $return = $obj->Values();
                            }
                        } else {
                            $return = $obj->GetAllValues();
                        }
                    }

                } else $this->addError(__FUNCTION__, "No class with given name!", $object);
            }
        }
        DATA::setSuccess((Checker::isArray($return)));
        return $return;
    }


    /**
     * Function CALL
     * for making function calls.
     * @param $action
     * @param $pars
     * @return array
     */
    public function CALL($action, $pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        if(Checker::isString($action, false, $errorInfo) &&  method_exists($this, $action) &&
            is_callable(array($this, $action)) && $this->AUTHENTICATE())
        {
            $return = $this->$action($pars);
        }
        return $return;
    }

    /**
     * Function AUTHENTICATE
     * for authenticating call.
     * @return bool Was authentication successful.
     */
    protected function AUTHENTICATE()
    {
        return false;
    }


}
