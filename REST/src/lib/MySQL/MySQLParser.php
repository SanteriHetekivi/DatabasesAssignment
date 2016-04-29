<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 14.22
 */

/**
 * Class MySQLParser
 * for parsing SQL queries.
 */
class MySQLParser
{
    private static function ERROR_INFO($FUNCTION){ return array(Err::FILE => __FILE__, Err::FUNC => $FUNCTION); }
    /**
     * Function Select
     * for parsing MySQL select query.
     * @param string|array $columns Columns for the query.
     * @param string $table Table for the query.
     * @return bool|string Query if successful false if not.
     */
    public static function Select($_columns = "*", $_table, $_where = false, $_order = false)
    {
        $return = false;
        $columns = false;
        $table = false;
        $order = false;
        $sql = "SELECT ";
        $columns = self::Columns($columns);
        $table = self::Table($table);
        if($_where) $where = self::Where($_where);
        
        if($columns && $table) $return = $sql;
        
        return $return;
    }

    /**
     * Function Columns
     * for making columns sql string,
     * from given $columns array or string.
     * @param array|string $columns Column names as a array or string.
     * @return bool|string SQL string if successful and false if not.
     */
    public static function Columns($columns)
    {
        $result = false;
        if(Checker::isString($columns))
        {
            if(Checker::Contains($columns, Setup::DECIMETER, false))
            {
                $columns = explode(Setup::DECIMETER, $columns);
            }
            else $columns = array($columns);
        }
        if(Checker::isArray($columns,false, self::ERROR_INFO(__FUNCTION__)))
        {
            $cols = array();
            foreach($columns as $column)
            {
                $col = MySQLParser::Column($column);
                if($col) $cols[] = $col;
                else break;
            }
            if(count($columns) === count($cols))
            {
                $result = implode(" ,", $cols);
            }
        }
        return $result;
    }

    /**
     * Function Table
     * for checking and formatting table name.
     * @param string $table table name.
     * @return bool|string Formatted table name or false if check did not pass.
     */
    public static function Table($table)
    {
        $return = false;
        $table = self::escapeName($table);
        if(MySQLChecker::isTable($table, self::ERROR_INFO(__FUNCTION__))) $return = $table;
        return $return;
    }

    /**
     * Function Column
     * for checking and formatting column name.
     * @param string $column Columns name.
     * @return bool|string Formatted column name if successful else false.
     */
    public static function Column($column)
    {
        $return = false;
        if($column != "*") $column = self::escapeName($column);
        if(MySQLChecker::isColumn($column, self::ERROR_INFO(__FUNCTION__))) $return = $column;
        return $return;
    }

    /**
     * Function Action
     * for parsing action.
     * @param string $action MySQL query action.
     * @return bool|string Formatted action if successful else false.
     */
    public static function Action($action)
    {
        $return = false;
        if(Checker::isString($action, false, false)) $action = strtoupper($action);
        if(MySQLChecker::isAction($action, self::ERROR_INFO(__FUNCTION__))) $return = $action;
        return $return;
    }

    /**
     * Function Operator
     * for parsing operator.
     * @param string $operator MySQL query operator.
     * @return bool|string Formatted operator if successful else false.
     */
    public static function Operator($operator)
    {
        $return = false;
        if(MySQLChecker::isOperator($operator, self::ERROR_INFO(__FUNCTION__))) $return = $operator;
        return $return;
    }

    /**
     * Function Value
     * for parsing value.
     * @param string|int|double|float $value Value to parse.
     * @return float|int|null|string Formatted value if successful else false.
     */
    public static function Value($value)
    {
        $return = NULL;
        if(MySQLChecker::isValue($value, self::ERROR_INFO(__FUNCTION__))) $return = (string)$value;
        return $return;
    }

    public static function Values($values)
    {
        $return = false;
        $errorInfo = self::ERROR_INFO(__FUNCTION__);
        if(Checker::isArray($values, false, $errorInfo))
        {
            $query = array();
            foreach($values as $column => $value)
            {
                $column = self::Columns($column);
                $name = trim($column, Setup::ESCAPE_NAME)."Value";
                $value = self::Value($value);
                if($column !== false && $value !== NULL)
                {
                    $query[MySQLQuery::COLUMNS][$column] = ":".$name;
                    $query[Where::VALUES][$name] = $value;
                }
            }
            if(isset($query[MySQLQuery::COLUMNS]) && isset($query[Where::VALUES])
                && count($values) === count($query[MySQLQuery::COLUMNS])) $return = $query;
            else
            {
                var_dump($values);
                echo "<br><br>";
                var_dump($query);
                die("");
            }
        }
        return $return;
    }
    /**
     * Function Conjunction
     * for parsing conjunction.
     * @param string $conjunction Conjunction to parse.
     * @return bool|string Formatted conjunction if successful else false.
     */
    public static function Conjunction($conjunction)
    {
        $return = false;
        if(Checker::isString($conjunction, false)) $conjunction = strtoupper($conjunction);
        if(MySQLChecker::isConjunction($conjunction, self::ERROR_INFO(__FUNCTION__))) $return = $conjunction;
        return $return;
    }

    /**
     * Function escapeName
     * for escaping name query names.
     * @param string $name Name of column/table.
     * @return bool|string Escaped version of name if successful else false.
     */
    public static function escapeName($name)
    {
        $return = false;
        if(Checker::isString($name)) $return = Setup::ESCAPE_NAME . $name . Setup::ESCAPE_NAME;
        return $return;
    }

    /**
     * Function idName
     * @param string $table Name of the table.
     * @return string Id of the table.
     */
    public static function idName($table)
    {
        $return = NULL;
        if(MySQLChecker::isTable($table))
        {
            $return = $table."Id";
        }
        return $return;
    }

    /**
     * Function Order
     * for parsing order keyword.
     * @param string $keyword Keyword or order.
     * @return bool|string Formatted order keyword if successful else false.
     */
    public static function Order($keyword)
    {
        $return = false;
        if(Checker::isString($keyword, false)) $keyword = strtoupper($keyword);
        if(MySQLChecker::isOrder($keyword, self::ERROR_INFO(__FUNCTION__))) $return = $keyword;
        return $return;
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

    public static function ID($id, $errorInfo = false)
    {
        $id = Parser::Int($id);
        if(MySQLChecker::isId($id, $errorInfo))
        {
            return $id;
        }
        return false;
    }
}
