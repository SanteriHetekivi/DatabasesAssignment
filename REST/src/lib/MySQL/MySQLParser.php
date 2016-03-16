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
class MySQLParser extends Root
{
    /**
     * Function Select
     * for parsing MySQL select query.
     * @param string|array $columns Columns for the query.
     * @param string $table Table for the query.
     * @return bool|string Query if successful false if not.
     */
    public static function Select($columns = "*", $table)
    {
        $return = false;

        $sql = "SELECT ";
        $cols = MySQLParser::Columns($columns);
        if($cols) $sql .= $cols;
        $tab = MySQLParser::Table($table);
        if($tab) $sql .= " FROM " . $tab;
        
        if($cols && $tab) $return = $sql;
        
        return $return;
    }

    /**
     * Function Columns
     * for making columns sql string,
     * from given $columns array or string.
     * @param array|string $columns Column names as a array or string.
     * @return bool|string SQL string if successful and false if not.
     */
    private static function Columns($columns)
    {
        $result = false;
        if(Checker::isString($columns))
        {
            if(Checker::Contains($columns, SetupRoot::$DECIMETER))
            {
                $columns = explode(SetupRoot::$DECIMETER, $columns);
            }
            else $columns = array($columns);
        }
        if(Checker::isArray($columns,false))
        {
            $cols = array();
            foreach($columns as $column)
            {
                $col = MySQLParser::Column($column);
                if($col) $cols[] = $col;
                else
                {
                    MySQLParser::addError(__FUNCTION__, "Given column is not suitable!", $column);
                    break;
                }
            }
            if(count($columns) === count($cols))
            {
                $result = implode(" ,", $cols);
            }
        }
        else MySQLParser::addError(__FUNCTION__, "Given columns are not array or is empty!", $columns);
        return $result;
    }

    /**
     * Function Table
     * for checking and formatting table name.
     * @param string $table table name.
     * @return bool|string Formatted table name or false if check did not pass.
     */
    private static function Table($table)
    {
        $return = false;
        if(MySQLChecker::isTable($table))
        {
            $return = "`" . $table . "`";
        }
        else
        {
            MySQLParser::addError(__FUNCTION__, "Given table is not suitable!", $table);
        }
        return $return;
    }

    private static function Column($column)
    {
        $return = false;
        if(MySQLChecker::isColumn($column))
        {
            if($column === "*") $return = $column;
            else $return = "`" . $column . "`";
        }
        else
        {
            MySQLParser::addError(__FUNCTION__, "Given column is not suitable!", $column);
        }
        return $return;
    }
    // TODO: Parsing functions.
}
