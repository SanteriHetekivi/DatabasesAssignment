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
     * Function ERROR_INFO
     * for making ERROR_INFO data.
     * @param string $FUNCTION Name of the function.
     * @return array ERROR_INFO data.
     */
    private static function ERROR_INFO($FUNCTION){ return array(Err::FILE => __FILE__, Err::FUNC => $FUNCTION); }
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
        if(Checker::isArray($errors, true, self::ERROR_INFO(__FUNCTION__)))
        {
            ErrorCollection::$errors = array();
            foreach($errors as $error)
            {
                $success = ErrorCollection::addErr($error);
                if($success === false) break;
            }
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
        $errorInfo = self::ERROR_INFO(__FUNCTION__);
        if(Checker::isString($file, true, $errorInfo) && Checker::isString($func, true, $errorInfo) &&
            Checker::isString($message, true, $errorInfo))
        {
            ErrorCollection::$errors[] = new Err($file, $func, $message, $variable);
            $success = true;
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
        if(Checker::isObject($error, "Err", false, self::ERROR_INFO(__FUNCTION__)))
        {
            ErrorCollection::$errors[] = $error;
            $success = true;
        }
        return $success;
    }

    /**
     * Function addErrorInfo
     * for adding error with info data.
     * @param array $ERROR_INFO Info containing filename and function.
     * @param string $message Message for error.
     * @param string $variable Variable for error.
     * @return bool Was operation successful.
     */
    public static function addErrorInfo($ERROR_INFO, $message = "", $variable = "")
    {
        if(Checker::isArray($ERROR_INFO) && isset($ERROR_INFO[Err::FILE]) && isset($ERROR_INFO[Err::FUNC]))
        {
            return ErrorCollection::addError($ERROR_INFO[Err::FILE], $ERROR_INFO[Err::FUNC], $message, $variable);
        }
        else
        {
            self::addError(__FILE__, __FUNCTION__, "Incorrect ERROR INFO!", $ERROR_INFO);
            return false;
        }
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
        if(Checker::isArray($errors, true, self::ERROR_INFO(__FUNCTION__)))
        {
            $success = true;
            foreach($errors as $error)
            {
                $success = ErrorCollection::addErr($error);
                if($success === false) break;
            }
        }

        if($success === false)
        {
            ErrorCollection::setErrors($old_errors);
        }
        return $success;
    }
}
