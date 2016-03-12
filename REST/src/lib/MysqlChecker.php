<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 13.31
 */
class MySQLChecker extends Checker
{
    public function isTable($table)
    {
        return $this->isString($table, false);
    }

    public function isId($id)
    {
        return $this->isInt($id,true,false);
    }

    public function isColumn($column)
    {
        return $this->isString($column, false);
    }

    public function isValue($value)
    {
        return $this->isVariable($value, false);
    }

    public function isOperator($operator)
    {
        if($this->isString($operator,false) === false) return false;
        $operators = "=><";
        return  strspn($operator,$operators) === strlen($operator);
    }

}