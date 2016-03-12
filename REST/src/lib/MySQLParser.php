<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 14.22
 */
class MySQLParser
{
    private $checker;

    public function __construct()
    {
        $this->checker = new MySQLChecker();
    }

    public function GET($args)
    {
        $sql        = false;
        $where      = false;
        $whereArgs  = array();
        if($this->checker->isArray($args,false) === false) return false;
        if(isset($args["table"])) $table = $this->Table($args["table"]);
        else return false;
        if(isset($args["columns"]) && $this->checker->isString($args["columns"], false))
        {
            $columns = $this->Columns($args["columns"]);
        }
        else $columns = $this->Column("*");
        if(isset($args["id"]))
        {
            $tmpId = $this->Id($args["id"]);
            if($tmpId)
            {
                $whereArgs[] = array("column" => $table."Id", "operator" => "=", "value" => $tmpId);
            }
        }

        if(isset($args["whereColumns"]) && isset($args["whereOperators"]) && isset($args["whereValues"])) {
            $whereColumn = $args["whereColumns"];
            $whereOperator = $args["whereOperators"];
            $whereValue = $args["whereValues"];
            if($this->checker->Contains($whereColumn.$whereOperator.$whereValue, ","))
            {
                $whereColumns = explode(",",$whereColumn);
                $whereOperators = explode(",", $whereOperator);
                $whereValues = explode(",", $whereValue);
                $whereArgs[] = $this->WhereArray($whereColumns, $whereOperators, $whereValues);
            }
            else
            {
                $whereArgs[] = array("column" => $whereColumn, "operator" => $whereOperator, "value" => $whereValue);
            }
        }
        if($this->checker->isArray($whereArgs,false)) $where = $this->Where($whereArgs);
        if($table && $columns)
        {
            $sql = "SELECT $columns FROM $table $where";
        }
        return $sql;
    }

    public function Table($table)
    {
        if($this->checker->isTable($table) === false) return false;
        return $table;
    }

    public function Id($id)
    {
        $id = intVal($id);
        if($this->checker->isId($id) === false) return false;
        return $id;
    }

    public function Column($column)
    {
        if($this->checker->isColumn($column) === false) return false;
        return "`$column`";
    }

    public function Columns($columns)
    {
        $return = false;
        if($this->checker->isString($columns,false))
        {
            if($this->checker->Contains($columns, ",")) $columns = explode(",", $columns);
            else $columns = array($columns);
        }

        if($this->checker->isArray($columns, false))
        {
            $cols = array();
            foreach($columns as $column)
            {
                $col = $this->Column($column);
                if($col) $cols[] = $col;
            }
            if($this->checker->isArray($cols, false))
            {
                $return = implode(",", $cols);
            }
        }
        return $return;
    }

    public function Operator($operator)
    {
        if($this->checker->isOperator($operator) === false) return false;
        return $operator;
    }

    public function Value($value)
    {
        if($this->checker->isValue($value) === false) return false;
        //return $conn->quote($value);
        return $value;
    }

    public function Where($where, $columns = false, $operators = false, $values = false)
    {
        if($this->checker->isArray($where, false) === false)
        {
            return $this->Where($this->WhereArray($columns, $operators, $values));
        }
        $return = "";
        $arguments = array();
        foreach($where as $argument)
        {
            if(isset($argument["column"]) && isset($argument["operator"]) && isset($argument["value"]))
            {

                $column     = $this->Column($argument["column"]);
                $operator   = $this->Operator($argument["operator"]);
                $value      = $this->Value($argument["value"]);
                if($column && $operator && $value)
                {
                    $arg = "{$column} {$operator} {$value}";
                    $arguments[] = $arg;
                }
            }
        }

        if($this->checker->isArray($arguments,false))
        {
            $return = "WHERE " . implode(" AND ", $arguments);
        }

        return $return;
    }

    private function WhereArray($columns, $operators, $values)
    {
        if(($this->checker->isArray($columns, false) ||
                $this->checker->isArray($operators, false) ||
                $this->checker->isArray($values, false)) === false)   return false;
        $return = array();
        foreach($columns as $key => $column)
        {
            if(isset($operators[$key]) && isset($values[$key]))
            {
                $operator   = $operators[$key];
                $value      = $values[$key];
                $return[] = array(
                    "column"      => $column,
                    "operator"  => $operator,
                    "value"     => $value
                );
            }
        }
        return $return;
    }

}