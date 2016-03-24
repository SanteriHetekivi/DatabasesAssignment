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
     * @var string Filename.
     */
    protected $FILE;
    /**
     * Function ERROR_INFO
     * for making ERROR_INFO data.
     * @param string $FUNCTION Name of the function.
     * @return array ERROR_INFO data.
     */
    protected function ERROR_INFO($FUNCTION){ return array(Err::FILE => $this->FILE, Err::FUNC => $FUNCTION); }
    /**
     * Function addError
     * for adding error to ErrorCollection.
     * @param string $func String of the error function.
     * @param string $message String of the error message.
     * @param object|string $variable Any object or variable.
     * @return bool Success of the function.
     */
    protected function addError($func = "", $message = "", $variable = "")
    {
        $success = ErrorCollection::addError($this->FILE, $func, $message, $variable);
        return $success;
    }

    /**
     * Root constructor.
     */
    protected function __construct()
    {
        $this->FILE = __FILE__;
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
     * for checking if given variable is object and
     * if $options is array or string also is that object's classname in options
     * @param object $obj Variable to test.
     * @param string $option Allowed class.
     * @param bool|string $parent Allowed parent. (Optional)
     * @param bool|string $addErrorFunction Name of function for error. (Optional)
     * @return bool Result of the check.
     */
    protected function isObject($obj, $option, $parent = false, $addErrorFunction = false)
    {
        $success = Checker::isObject($obj, $option, $parent);
        if($success == false && Checker::isString($addErrorFunction) && Checker::isString($option))
        {
            $message = "Given object was not $option";
            $message .= ($parent)?" and/or $parent 's child!":"!";
            $this->addError($addErrorFunction, $message,  $obj);
        }
        return $success;
    }
}
