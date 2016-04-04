<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.39
 */
use Psr\Http\Message\RequestInterface;

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
     * @param array $pars Key: 0 = Object name, Key: 1 Id of object (Optional)
     * @return array Values for given column and id if set.
     */
    public function GET($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        $object = $this->getObject($pars);
        if($object)
        {
            $id = $this->getId($pars);
            if($id)
            {
                $object->setID($id);
                if($object->SELECT())
                {
                    $return = $object->Values();
                }
            }
            else
            {
                $return = $object->GetAllValues();
            }
        }
        DATA::setSuccess((Checker::isArray($return)));
        return $return;
    }

    /**
     * Function ADD
     * for adding new row to database.
     * @param array $pars Key: 0 = Object name, Key: values = values for new row.
     * @return array Values for given column and id if set.
     */
    public function ADD($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        $object = $this->getObject($pars);
        if($object)
        {
            $values = $this->getValues($pars);
            if($values)
            {
                $object->setValues($values);
                DATA::setSuccess($object->COMMIT());
                $return = $object->Values();
            }
        }
        return $return;
    }


    /**
     * Function UPDATE
     * for updating existing row from database.
     * @param array $pars Key: 0 = Object name, Key: 1 Id of object, Key: values = values for new row.
     * @return array Values for given column and id if set.
     */
    public function UPDATE($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        if($object && $id)
        {
            $object->setID($id);
            if($object->SELECT())
            {
                $values = $this->getValues($pars);
                if($values)
                {
                    $object->setValues($values);
                    DATA::setSuccess($object->COMMIT());
                    $return = $object->Values();
                }
            }
        }
        return $return;
    }

    /**
     * Function DELETE
     * for deleting existing row from database.
     * @param array $pars Key: 0 = Object name, Key: 1 Id of object, Key: values = values for new row.
     * @return array Values for given column and id if set.
     */
    public function DELETE($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        if($object && $id)
        {
            $object->setID($id);
            if($object->SELECT())
            {
                DATA::setSuccess($object->DELETE());
            }
        }
        return $return;
    }

    /**
     * Function getObject
     * for getting object from parameters.
     * @param array $pars Parameters.
     * @return bool|MySQLObject Object for name or false if not found.
     */
    private function getObject($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = false;
        if (Checker::isArray($pars,false, $errorInfo) && isset($pars[0]) && Checker::isString($pars[0], false, $errorInfo))
        {
            $object = $pars[0];
            if (class_exists($object))
            {
                    $obj = new $object();
                    if (Checker::isObject($obj, false, "MySQLObject", $errorInfo))
                    {
                        $return = $obj;
                    }
            } else $this->addError(__FUNCTION__, "No class with given name!", $object);
        }
        return $return;
    }

    /**
     * Function getId
     * for getting id from parameters.
     * @param array $pars Parameters.
     * @return bool|int Id or false if not found.
     */
    private function getId($pars)
    {
        $return  = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if (Checker::isArray($pars,false, $errorInfo) && isset($pars[1]))
        {
            $return = Parser::Int($pars[1], $errorInfo);
        }
        return $return;
    }

    /**
     * Function getValues
     * for getting values from parameters.
     * @param array $pars Parameters.
     * @return bool|array Values as array or false if not found.
     */
    private function getValues($pars)
    {
        $return = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if (Checker::isArray($pars,false, $errorInfo) && isset($pars["values"]) && Checker::isArray($pars["values"], true, $errorInfo))
        {
            $return = $pars["values"];
        }
        return $return;
    }


    /**
     * Function CALL
     * for making function calls.
     * @param String $action Action to run.
     * @param $pars array Array of extra parameters.
     * @return array
     */
    public function CALL($action, $pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        if($this->AUTHENTICATE() && Checker::isString($action, false, $errorInfo))
        {
            if(method_exists($this, $action) && is_callable(array($this, $action)))
            {
                $return = $this->$action($pars);
            }
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
