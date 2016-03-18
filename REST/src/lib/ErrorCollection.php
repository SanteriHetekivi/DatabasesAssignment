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
    private static $errors;

    /**
     * Function Errors
     * for getting all errors.
     * @return array Err array.
     */
    public static function Errors()
    {
        return ErrorCollection::$errors;
    }

    /**
     * Function setErrors
     * for setting errors array.
     * @param array $errors Err array to set as errors.
     * @return bool Success of the function.
     */
    public static function setErrors($errors)
    {
        $success = false;
        $old_errors = ErrorCollection::Errors();
        if(Checker::isArray($errors))
        {
            ErrorCollection::$errors = array();
            foreach($errors as $error)
            {
                $success = ErrorCollection::addErr($error);
                if($success === false) break;
            }
        }
        else
        {
            ErrorCollection::addErr(new Err(__FILE__, __FUNCTION__, "Given error where not array"));
        }

        if($success === false)
        {
            ErrorCollection::$errors = $old_errors;
        }
        return $success;
    }

    /**
     * Function hasErrors
     * for checking if ErrorCollection has errors.
     * @return bool Does ErrorCollection have errors.
     */
    public static function hasErrors()
    {
        return Checker::isArray(ErrorCollection::Errors(), false);
    }

    /**
     * Function ErrorsData
     * for getting data array with data from
     * every error.
     * @return array Errors data.
     */
    public static function ErrorsData()
    {
        $return = array();
        if(ErrorCollection::hasErrors())
        {
            foreach(ErrorCollection::Errors() as $error)
            {
                $return[] = $error->GET();
            }
        }
        return $return;

    }
    /**
     * Function addError
     * for adding error from values.
     * @param string $file String of the errors file.
     * @param string $func String of the error function.
     * @param string $message String of the error message.
     * @param object|string $variable Any object or variable.(Optional)
     * @return bool Success of the function.
     */
    public static function addError($file, $func, $message, $variable = "")
    {
        $success = false;
        if(Checker::isString($file) && Checker::isString($func) && Checker::isString($message))
        {
            ErrorCollection::$errors[] = new Err($file, $func, $message, $variable);
            $success = true;
        }
        else
        {
            ErrorCollection::addErr(new Err(__FILE__, __FUNCTION__,
                "All info values for error needs to be strings and variable any variable",
                array($file, $func, $message, $variable)
            ));
        }
        return $success;
    }

    /**
     * Function addErr
     * for adding error from Err object.
     * @param Err $error Err object to add.
     * @return bool Success of the function.
     */
    public static function addErr($error)
    {
        $success = true;
        if(Checker::isObject($error, "Err"))
        {
            ErrorCollection::$errors[] = $error;
            $success = true;
        }
        return $success;
    }

    /**
     * Function addErrors
     * for adding array of errors.
     * @param array $errors Array of Err classes.
     * @return bool Success of the function.
     */
    public static function addErrors($errors)
    {
        $success = false;
        $old_errors = ErrorCollection::Errors();
        if(Checker::isArray($errors))
        {
            $success = true;
            foreach($errors as $error)
            {
                $success = ErrorCollection::addErr($error);
                if($success === false) break;
            }
        }
        else
        {
            ErrorCollection::addErr(new Err(__FILE__, __FUNCTION__, "Given error where not array"));
        }

        if($success === false)
        {
            ErrorCollection::setErrors($old_errors);
        }
        return $success;
    }
}
