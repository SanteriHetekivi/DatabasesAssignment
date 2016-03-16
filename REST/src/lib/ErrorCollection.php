<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.39
 */

/**
 * Class ErrorCollection
 * for storing errors.
 */
class ErrorCollection
{
    /**
     * @var array of Err classes.
     */
    private $errors;

    /**
     * Function Errors
     * for getting all errors.
     * @return array Err array.
     */
    public function Errors()
    {
        return $this->errors;
    }

    /**
     * Function setErrors
     * for setting errors array.
     * @param array $errors Err array to set as errors.
     * @return bool Success of the function.
     */
    public function setErrors($errors)
    {
        $success = false;
        $old_errors = $this->Errors();
        if(Checker::isArray($errors))
        {
            $this->errors = array();
            foreach($errors as $error)
            {
                $success = $this->addError($error);
                if($success === false) break;
            }
        }
        else
        {
            $this->addError(new Err(__FILE__, __FUNCTION__, "Given error where not array"));
        }

        if($success === false)
        {
            $this->errors = $old_errors;
        }
        return $success;
    }

    /**
     * Function addError
     * for adding error.
     * @param Err|bool $error that will be added to errors.
     * @param string $file String of the errors file. (Optional)
     * @param string $func String of the error function. (Optional)
     * @param string $message String of the error message. (Optional)
     * @param object|string $variable Any object or variable. (Optional)
     * @return bool Success of the function.
     */
    public function addError($error = false, $file = "", $func = "", $message = "", $variable = "")
    {
        $success = true;
        if(Checker::isObject($error, "Err"))
        {
            $this->errors[] = $error;
            $success = true;
        }
        else if(Checker::isString($file) && Checker::isString($func) && Checker::isString($message) && Checker::isVariable($variable))
        {
            $this->errors[] = new Err($file, $func, $message);
            $success = true;
        }
        else
        {
            $this->addError(new Err(__FILE__, __FUNCTION__, "Can only add Err class to ErrorCollection"));
        }
        return $success;
    }

    /**
     * Function addErrors
     * for adding array of errors.
     * @param array $errors Array of Err classes.
     * @return bool Success of the function.
     */
    public function addErrors($errors)
    {
        $success = false;
        $old_errors = $this->Errors();
        if(Checker::isArray($errors))
        {
            $success = true;
            foreach($errors as $error)
            {
                $success = $this->addError($error);
                if($success === false) break;
            }
        }
        else
        {
            $this->addError(new Err(__FILE__, __FUNCTION__, "Given error where not array"));
        }

        if($success === false)
        {
            $this->setErrors($old_errors);
        }
        return $success;
    }
}