<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 18.3.2016
 * Time: 17.50
 */

/**
 * Class Where
 * for making where query.
 */
class WhereOld extends Root
{
    /**
     * @var string|bool Column name.
     */
    private $column;

    /**
     * Function Column
     * for getting column name.
     * @return bool|string
     */
    private function Column()
    {
        return $this->column;
    }

    /**
     * Function setColumn
     * for setting column name.
     * @param string $column Name of the column.
     * @return bool Success of the function.
     */
    private function setColumn($column)
    {
        $column = MySQLParser::Column($column);
        if($column) $this->column = $column;
        return (bool)$column;
    }

    /**
     * @var bool|string Operator for the query (=><).
     */
    private $operator;

    /**
     * Function Operator
     * for returning operator for the query.
     * @return bool|string Operator for the query (=><).
     */
    private function Operator()
    {
        return $this->operator;
    }

    /**
     * Function setOperator
     * for setting query's operator.
     * @param string $operator Operator for the query.
     * @return bool Success of the function.
     */
    private function setOperator($operator)
    {
        $operator = MySQLParser::Operator($operator);
        if($operator) $this->operator = $operator;
        return ($operator);
    }

    /**
     * @var bool|string Value for the query.
     */
    private $value;

    /**
     * Function Value
     * for returning value of the query.
     * @return bool|string
     */
    private function Value()
    {
        return $this->value;
    }

    /**
     * Function setValue
     * for setting query's value.
     * @param string|bool|int $value.
     * @return bool
     */
    private function setValue($value)
    {
        $value = MySQLParser::Value($value);
        if($value !== false) $this->value = $value;
        return $value;
    }

    private $conjunction;

    private function Conjunction()
    {
        return $this->conjunction;
    }

    private function setConjunction($conjunction)
    {
        $conjunction = MySQLParser::Conjunction($conjunction);
        if($conjunction) $this->conjunction = $conjunction;
        return ($conjunction);
    }

    public function __construct($column, $value, $operator = Setup::DEFAULT_OPERATOR, $conjunction = Setup::DEFAULT_CONJUNCTION)
    {
        parent::__construct();
        $this->FILE = __FILE__;
        $this->column = false;
        $this->value = false;
        $this->operator = false;
        $this->conjunction = false;
        $this->setColumn($column);
        $this->setValue($value);
        $this->setOperator($operator);
        $this->setConjunction($conjunction);
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->column);
        unset($this->value);
        unset($this->operator);
        unset($this->conjunction);
    }

    public function GET($link = true)
    {
        $return = false;
        $conjunction = $this->Conjunction();
        $column = $this->Column();
        $operator = $this->Operator();
        $value = $this->Value();
        if($column !== false && $operator !== false && $value !== false && $conjunction !== false)
        {
            if($link === false) $conjunction = "";
            else $conjunction = " $conjunction";
            $return = $conjunction . " " . $column . " " . $operator . " " . $value;
        }
        else
        {
            $this->addError(__FUNCTION__, "One of where's members was false!", array("conjunction" => $conjunction,
                "column" => $column, "operator" => $operator, "value" => $value));
        }
        return $return;
    }
}