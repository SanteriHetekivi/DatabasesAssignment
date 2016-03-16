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
     * @return bool Was acceptable.
     */
    public static function isTable($table)
    {
        return Checker::isString($table, false);
    }

    /**
     * Function isId
     * for checking is given variable
     * acceptable id for MySQL table.
     * @param int $id Id for the Mysql table.
     * @return bool Was acceptable.
     */
    public static function isId($id)
    {
        return Checker::isInt($id,true,false);
    }

    /**
     * Function isColumn
     * for checking is given variable
     * acceptable name for MySQL column.
     * @param string $column Name of the MySQL table.
     * @return bool Was acceptable.
     */
    public static function isColumn($column)
    {
        return Checker::isString($column, false);
    }

    /**
     * Function isValue
     * for checking is given variable
     * acceptable value for MySQL table.
     * @param object $value Variable to check.
     * @return bool Was acceptable.
     */
    public static function isValue($value)
    {
        return Checker::isVariable($value, false);
    }

    /**
     * Function isOperator
     * for checking is given variable
     * acceptable operator for MySQL table.
     * @param string $operator Operator.
     * @return bool Was acceptable.
     */
    public static function isOperator($operator)
    {
        if(Checker::isString($operator,false) === false) return false;
        $operators = "=><";
        return  strspn($operator,$operators) === strlen($operator);
    }

    /**
     * Function isSupportedType
     * for checking if given column
     * type is supported.
     * @param string $type MySQL data type.
     * @return bool Was given type supported.
     */
    public static function isSupportedType($type)
    {
        return Checker::isString($type) && in_array($type, MySQLColumn::$supportedTypes);
    }
}