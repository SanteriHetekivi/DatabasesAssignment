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
    public function Select($columns = "*", $table = false)
    {
        $return = false;

        $sql = "SELECT";


    }

    /**
     * Function Columns
     * for making columns sql string,
     * from given $columns array or string.
     * @param array|string $columns Column names as a array or string.
     * @return bool|string SQL string if successful and false if not.
     */
    private function Columns($columns)
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
                $col = MySQLChecker::isColumn($column);
                if($col) $cols[] = $col;
                else
                {
                    $this->addError(__FUNCTION__, "Given column is not suitable!", $column);
                    break;
                }
            }
            if(count($columns) === count($cols))
            {
                $result = implode(" ,", $cols);
            }
        }
        else $this->addError(__FUNCTION__, "Given columns are not array or is empty!", $columns);
        return $result;
    }
    // TODO: Parsing functions.
}