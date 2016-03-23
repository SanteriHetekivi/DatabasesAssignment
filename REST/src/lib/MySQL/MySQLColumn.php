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
        "id",
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
        if($type === "id") $value = Parser::Int($value);
        if($type === "bool") $value = (bool)$value;
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

    private $linked;

    public function Linked()
    {
        return $this->linked;
    }

    public function setLinked($object)
    {
        $success = false;
        if(Checker::isObject($object, false, $this->ERROR_INFO(__FUNCTION__)))
        {
            $this->linked = $object;
            $success = true;
        }
        return $success;
    }


    /**
     * MySQLColumn constructor.
     * @param string $name  Name of column.
     * @param string|bool|int|double $value Value for column.
     * @param string $type Type of column.
     */
    public function __construct($name, $value, $type = "VARCHAR", $linked = false)
    {
        parent::__construct();
        $this->FILE = __FILE__;
        $this->setName($name);
        $this->setValue($value);
        $this->setType($type);
        if($linked) $this->setLinked($linked);
    }

    /**
     * MySQLColumn destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
        unset($this->type);

    }

    public function CHECK($value=NULL)
    {
        if($value === NULL) $value = $this->value;
        return true;
    }

    private function PARSE($value)
    {
        $return = false;
        if($this->CHECK($value))
        {
            $type = $this->type;
            if($type == "BOOL") $value = ($value)?"1":"0";
            $value = (string)$value;
            $return = $value;
        }
        return $return;
    }
    // TODO Add size.
    // TODO Add empty.
    // TODO Add checkers.
}