<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 17.16
 */

/**
 * Class Root
 * for managing errors
 * and other low end
 * operations.
 */
class Root
{

    /**
     * Function addError
     * for adding error to ErrorCollection.
     * @param string $func String of the error function.
     * @param string $message String of the error message.
     * @param object|string $variable Any object or variable.
     * @return bool Success of the function.
     */
    public function addError($func = "", $message = "", $variable = "")
    {
        $success = ErrorCollection::addError(false, __FILE__, $func, $message, $variable);
        return $success;
    }

    /**
     * Root constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Root destructor.
     */
    public function __destruct()
    {
        unset($this->errorCollection);
    }

    /**
     * Function isObject
     * for checking if given object
     * is object and correct one.
     * @param object $obj Object to test.
     * @param string $option Name of object.
     * @param bool|string $addErrorFunction Name of function for error. (Optional)
     * @return bool Did given object pass checks.
     */
    protected function isObject($obj, $option, $addErrorFunction = false)
    {
        $success = Checker::isObject($obj,$option);
        if($success == false && Checker::isString($addErrorFunction) && Checker::isString($option))
        {
            $this->addError($addErrorFunction, "Given object was not $option!", $obj);
        }
        return $success;
    }
}