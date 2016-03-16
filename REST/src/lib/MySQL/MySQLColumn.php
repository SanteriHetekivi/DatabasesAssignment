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
     * @var array of supported types.
     */
    public static $supportedTypes = array(
        "VARCHAR"
    );
    /**
     * Function isSupportedType
     * for checking if given type
     * is supported.
     * @param string $type Type to test.
     * @return bool Given type was supported.
     */
    private function isSupportedType($type)
    {
        $success = MySQLChecker::isSupportedType($type);
        if($success === false) $this->addError(__FUNCTION__, "Given type is not supported.", $type);
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
        if($this->isSupportedType($type))
        {
            $this->type = $type;
            $success = true;
        }
        return $success;
    }
    // TODO Add size.
    // TODO Add empty.
    // TODO Add checkers.
}