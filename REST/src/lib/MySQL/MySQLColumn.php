<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.50
 */
class MySQLColumn extends Root
{
    /**
     * Array of supported types.
     */
    const SUPPORTED_TYPES = array(
        "VARCHAR",
        "ID",
        "BOOL"
    );

    /**
     * @var string Name of column.
     */
    private $name;

    /**
     * Function Name
     * for getting column name.
     * @return string Name of column.
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * Function setName
     * for setting column name
     * to given value
     * @param string $name Name for column.
     * @return bool Success of the function.
     */
    public function setName($name)
    {
        $success = false;
        if(Checker::isString($name, false, $this->ERROR_INFO(__FUNCTION__)))
        {
            $this->name = $name;
            $success = true;
        }
        return $success;
    }

    /**
     * @var string Value for column.
     */
    private $value;

    /**
     * Function Value
     * for getting column's value.
     * @return string|bool|int|double Value of rows column.
     */
    public function Value()
    {
        $type = $this->Type();
        $value = $this->value;
        if($type === "ID") $value = Parser::Int($value);
        if($type === "BOOL") $value = (bool)$value;
        return $value;
    }

    /**
     * Function setValue
     * for setting value.
     * @param string|bool|int|double $value
     * @return bool Success of the function
     */
    public function setValue($value)
    {
        $success = false;
        $value = $this->PARSE($value);
        if(Checker::isString($value, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $this->value = $value;
            if($this->linked)
            {
                $id = Parser::Int($value);
                if(MySQLChecker::isId($id))
                {
                    $this->linked->setValue($this->linked->IdName(), $id);
                    $this->linked->SELECT();
                }
            }
            $success = true;
        }
        return $success;
    }

    /**
     * @var string Type of the column data.
     */
    private $type;
    /**
     * Function Type
     * for getting type of the column data.
     * @return string Type of the column data.
     */
    public function Type()
    {
        return $this->type;
    }

    /**
     * Function setType
     * for setting columns data type.
     * @param string $type Data type.
     * @return bool Success of the function.
     */
    private function setType($type)
    {
        $success = false;
        if(MySQLChecker::isSupportedType($type, $this->ERROR_INFO(__FUNCTION__)))
        {
            $this->type = $type;
            $success = true;
        }
        return $success;
    }

    /**
     * @var object|bool Linked object for value.
     */
    private $linked;

    /**
     * Function Linked
     * for getting values
     * linked object.
     * @return bool|object Linked object for value or false if not set.
     */
    public function Linked()
    {
        return $this->linked;
    }

    /**
     * Function setLinked
     * for setting linked MySQLObject's child.
     * @param object $object Object to set.
     * @return bool Success of the function.
     */
    public function setLinked($object)
    {
        $success = false;
        if(Checker::isObject($object, false, "MySQLObject", $this->ERROR_INFO(__FUNCTION__)))
        {
            $this->linked = $object;
            $success = true;
        }
        return $success;
    }


    /**
     * MySQLColumn constructor.
     * @param string $name Name of column.
     * @param string|bool|int|double $value Value for column.
     * @param string $type Type of column. (Optional)
     * @param bool|object $linked MySQLObject's child to link. (Optional)
     */
    public function __construct($name, $value, $type = "VARCHAR", $linked = false)
    {
        parent::__construct();
        $this->FILE = __FILE__;
        $this->setName($name);
        $this->setValue($value);
        $this->setType($type);
        if($linked) $this->setLinked($linked);
        else $this->linked = false;
    }

    /**
     * MySQLColumn destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
        unset($this->type);
        unset($this->name);
        unset($this->value);
        unset($this->linked);
    }

    public function CHECK($value=NULL)
    {
        if($value === NULL) $value = $this->value;
        $success = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $type = $this->Type();
        if($type === "ID") $success = Checker::isInt($value, true, true, $errorInfo);
        elseif($type === "VARCHAR") $success = Checker::isString($value, true, $errorInfo);
        elseif($type === "BOOL")    $success = Checker::isBool($value, $errorInfo);
        else $success = true;
        return $success;
    }

    private function PARSE($value = null)
    {
        $return = false;
        if(is_null($value)) $value = $this->value;
        $type = $this->type;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if($type === "ID") $value = Parser::Int($value, $errorInfo);
        elseif($type === "BOOL") $value = Parser::Bool($value, $errorInfo);
        elseif($type === "VARCHAR") $value = Parser::String($value, $errorInfo);
        if($this->CHECK($value))
        {
            if($type == "BOOL") $value = ($value)?"1":"0";
            $value = Parser::String($value);
            $return = $value;
        }
        return $return;
    }
    // TODO Add size.
    // TODO Add empty.
    // TODO Add checkers.
}