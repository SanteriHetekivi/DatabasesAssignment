<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 13.31
 */

/**
 * Class MySQLChecker
 * for checking MySQL variables.
 */
class MySQLChecker extends Checker
{
    /**
     * Function isTable
     * for checking is given variable
     * acceptable name for MySQL table.
     * @param string $table Name of the MySQL table.
     * @param bool $errors Add errors.
     * @return bool Was acceptable.
     */
    public static function isTable($table, $errors = true)
    {
        $success = Checker::isString($table, false, $errors);
        if($success ===  false && $errors) MySQLChecker::addError(__FUNCTION__, "Given table name is not acceptable!", $table);
        return $success;
    }

    /**
     * Function isId
     * for checking is given variable
     * acceptable id for MySQL table.
     * @param int $id Id for the Mysql table.
     * @param bool $errors Add errors.
     * @return bool Was acceptable.
     */
    public static function isId($id, $errors = true)
    {
        $success = Checker::isInt($id,true,false, $errors);
        if($success ===  false && $errors) MySQLChecker::addError(__FUNCTION__, "Given id is not acceptable!", $id);
        return $success;
    }

    /**
     * Function isColumn
     * for checking is given variable
     * acceptable name for MySQL column.
     * @param string $column Name of the MySQL table.
     * @param bool $errors Add errors.
     * @return bool Was acceptable.
     */
    public static function isColumn($column, $errors = true)
    {
        $success = Checker::isString($column, false, $errors);
        if($success ===  false && $errors) MySQLChecker::addError(__FUNCTION__, "Given column name is not acceptable!", $column);
        return $success;
    }

    /**
     * Function isValue
     * for checking is given variable
     * acceptable value for MySQL table.
     * @param object $value Variable to check.
     * @param bool $errors Add errors.
     * @return bool Was acceptable.
     */
    public static function isValue($value, $errors = true)
    {
        $success = Checker::isVariable($value, false, $errors);
        if($success ===  false && $errors) MySQLChecker::addError(__FUNCTION__, "Given value is not acceptable!", $value);
        return $success;

    }

    /**
     * Function isOperator
     * for checking is given variable
     * acceptable operator for MySQL table.
     * @param string $operator Operator.
     * @param bool $errors Add errors.
     * @return bool Was acceptable.
     */
    public static function isOperator($operator, $errors = true)
    {
        $operators = "=><";
        $success = Checker::isString($operator,false, $errors);
        $success = $success && strspn($operator,$operators) === strlen($operator);
        if($success ===  false && $errors) MySQLChecker::addError(__FUNCTION__, "Given operator is not acceptable!", $operator);
        return $success;
    }

    /**
     * Function isSupportedType
     * for checking if given column
     * type is supported.
     * @param string $type MySQL data type.
     * @param bool $errors Add errors.
     * @return bool Was given type supported.
     */
    public static function isSupportedType($type, $errors = true)
    {
        $success = Checker::isString($type,$errors) && in_array($type, MySQLColumn::$supportedTypes);
        if($success ===  false && $errors) MySQLChecker::addError(__FUNCTION__, "Given type is not supported!",
            array("type" => $type, "supportedTypes" => MySQLColumn::$supportedTypes));
        return ;
    }

    /**
     * Function addError
     * for adding error to ErrorCollection.
     * @param string $func String of the error function.
     * @param string $message String of the error message.
     * @param object|string $variable Any object or variable.
     * @return bool Success of the function.
     */
    private static function addError($func = "", $message = "", $variable = "")
    {
        $success = ErrorCollection::addError(__FILE__, $func, $message, $variable);
        return $success;
    }
}