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
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was acceptable.
     */
    public static function isTable($table, $errorInfo = false)
    {
        $success = Checker::isString($table, false, $errorInfo);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given table name is not acceptable!", $table);
        return $success;
    }

    /**
     * Function isId
     * for checking is given variable
     * acceptable id for MySQL table.
     * @param int $id Id for the Mysql table.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was acceptable.
     */
    public static function isId($id, $errorInfo = false)
    {
        $success = Checker::isInt($id,true,false, $errorInfo);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given id is not acceptable!", $id);
        return $success;
    }

    /**
     * Function isColumn
     * for checking is given variable
     * acceptable name for MySQL column.
     * @param string $column Name of the MySQL table.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was acceptable.
     */
    public static function isColumn($column, $errorInfo = false)
    {
        $success = Checker::isString($column, false, $errorInfo);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given column name is not acceptable!", $column);
        return $success;
    }

    /**
     * Function isColumns
     * for checking if given column names are
     * acceptable names for MySQL columns.
     * @param array $columns Column names to check.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was acceptable.
     */
    public static function isColumns($columns, $errorInfo = false)
    {
        $success = Checker::isArray($columns, false, $errorInfo);
        if($success)
        {
            foreach($columns as $column)
            {
                $success = $success && MySQLChecker::isColumn($column, $errorInfo);
            }
        }
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given columns are not acceptable!", $columns);
        return $success;
    }

    /**
     * Function isValue
     * for checking is given variable
     * acceptable value for MySQL table.
     * @param object $value Variable to check.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was acceptable.
     */
    public static function isValue($value, $errorInfo = false)
    {
        $success = true;
        $success =  $success && Checker::isVariable($value, true, $errorInfo);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given value is not acceptable!", $value);
        return $success;

    }

    /**
     * Function isConjunction
     * for checking if given variable
     * is conjunction (AND, OR...). 
     * @param string $conjunction to check.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was given conjunction supported.
     */
    public static function isConjunction($conjunction, $errorInfo = false)
    {
        $success = Checker::isString($conjunction,false, $errorInfo);
        $success = $success && in_array($conjunction, Setup::CONJUNCTIONS);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given conjunction is not acceptable!",
            array("conjunction" => $conjunction, "conjunctions" => Setup::CONJUNCTIONS));
        return $success;
    }

    /**
     * Function isOperator
     * for checking is given variable
     * acceptable operator for MySQL table.
     * @param string $operator Operator.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was acceptable.
     */
    public static function isOperator($operator, $errorInfo = false)
    {
        $success = Checker::isString($operator,false, $errorInfo);
        $success = $success && strspn($operator,Setup::OPERATORS) === strlen($operator);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given operator is not acceptable!",
            array("operator" => $operator, "operators" => Setup::OPERATORS));
        return $success;
    }

    /**
     * Function isSupportedType
     * for checking if given column
     * type is supported.
     * @param string $type MySQL data type.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was given type supported.
     */
    public static function isSupportedType($type, $errorInfo = false)
    {
        $success = Checker::isString($type,$errorInfo) && in_array($type, MySQLColumn::SUPPORTED_TYPES);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given type is not supported!",
            array("type" => $type, "supportedTypes" => MySQLColumn::SUPPORTED_TYPES));
        return $success;
    }

    /**
     * Function isAction
     * for checking if given string is supported action.
     * @param string $action Name of the action.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was given action supported.
     */
    public static function isAction($action, $errorInfo = false)
    {
        $success = Checker::isString($action, false, $errorInfo);
        $success = $success && in_array($action, MySQLQuery::SUPPORTED_ACTIONS);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given action is not supported!",
            array("action" => $action, "supportedActions" => MySQLQuery::SUPPORTED_ACTIONS));
        return $success;
    }

    /**
     * Function isOrder
     * for checking if given keyword is supported.
     * @param string $keyword Keyword to check.
     * @param bool|array $errorInfo Array containing file and function names. (Optional)
     * @return bool Was given keyword supported.
     */
    public static function isOrder($keyword, $errorInfo = false)
    {
        $success = Checker::isString($keyword, false, $errorInfo);
        $success = $success && in_array($keyword, OrderBy::KEYWORDS);
        if($success ===  false && $errorInfo) ErrorCollection::addErrorInfo($errorInfo,"Given keyword is not supported!",
            array("action" => $keyword, "supportedActions" => OrderBy::KEYWORDS));
        return $success;
    }

}