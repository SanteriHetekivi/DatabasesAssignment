<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 19.3.2016
 * Time: 13.05
 */
class Where
{
    /**
     * Const QUERY for storing key for query.
     */
    const QUERY = "query";

    /**
     * Const VALUES for storing key for values.
     */
    const VALUES = "values";

    /**
     * @var array Keys in use.
     */
    private static $keys = array();

    /**
     * Function ERROR_INFO
     * for making ERROR_INFO data.
     * @param string $FUNCTION Name of the function.
     * @return array ERROR_INFO data.
     */
    private static function ERROR_INFO($FUNCTION){ return array(Err::FUNC => __FILE__, Err::FUNC => $FUNCTION); }

    /**
     * Function MAKE
     * for making Where query.
     * @param string|array $operations Operations for where query.
     * @return array|bool Query data if successful else false.
     */
    public static function MAKE($operations)
    {
        $return = false;
        if(Checker::isArray($operations, false, false))
        {
            if(Checker::isArray(end($operations)))
            {
               $return = self::MAKEFromArrays($operations);
            }
            else
            {
                $supportedKeys = array(0,1,2,3,Setup::WHERE_VALUE,Setup::WHERE_COLUMN,Setup::WHERE_CONJUNCTION,Setup::WHERE_OPERATOR);
                $keys = array_keys($operations);
                Where::$keys = array();
                if(empty(array_intersect($supportedKeys, $keys)))
                {
                    $return = self::MAKEFromNamedArray($operations);
                }
                else $return = self::MAKEFromArray($operations);
            }
        }
        //TODO elseif(Checker::isString($operations, false, false)) $return = self::MAKEFromString();
        else {}
        if($return !== false && isset($return[self::QUERY])) $return[self::QUERY] = " WHERE " . $return[self::QUERY];
        return $return;
    }

    /**
     * Function MAKEFromNamedArray
     * for making where query data from named array.
     * @param array $operations Operations for where query.
     * @return array|bool Query data if successful else false.
     */
    private static function MAKEFromNamedArray($operations)
    {
        $return = false;
        $query = "";
        $values = array();
        $first = true;
        foreach($operations as $column => $value)
        {
            $data = self::MAKEFromArray(array(Setup::WHERE_COLUMN => $column, Setup::WHERE_VALUE => $value), $first);
            if($first)$first = false;
            if(Checker::isArray($data, false) && isset($data[self::QUERY]) && isset($data[self::VALUES]))
            {
                $query .= $data[self::QUERY];
                $values += $data[self::VALUES];
            }
            else
            {
                ErrorCollection::addErrorInfo(self::ERROR_INFO(__FUNCTION__), "Given data was not correct array!", $data);
            }
        }
        if(count($values) === count($operations))
        {
            $return[self::QUERY] = $query;
            $return[self::VALUES] = $values;
        }
        return $return;
    }

    /**
     * Function MAKEFromArray
     * for making where query data from array.
     * @param array $operation Operation for where query.
     * @param bool $first Was given operation first one.
     * @return array|bool Query data if successful else false.
     */
    private static function MAKEFromArray($operation, $first = true)
    {
        $return = false;
        if(Checker::isArray($operation, false, self::ERROR_INFO(__FUNCTION__)))
        {
            $column = false;
            $value = false;
            $operator = Setup::DEFAULT_OPERATOR;
            $conjunction = Setup::DEFAULT_CONJUNCTION;
            if(isset($operation[Setup::WHERE_COLUMN])) $column = MySQLParser::Column($operation[Setup::WHERE_COLUMN]);
            elseif(isset($operation[0])) $column = MySQLParser::Column($operation[0]);
            if(isset($operation[Setup::WHERE_VALUE])) $value = MySQLParser::Value($operation[Setup::WHERE_VALUE]);
            elseif(isset($operation[1])) $value = MySQLParser::Value($operation[1]);
            if(isset($operation[Setup::WHERE_OPERATOR])) $operator = MySQLParser::Operator($operation[Setup::WHERE_OPERATOR]);
            elseif(isset($operation[2])) $operator = MySQLParser::Operator($operation[2]);
            if(isset($operation[Setup::WHERE_CONJUNCTION])) $conjunction = MySQLParser::Conjunction($operation[Setup::WHERE_CONJUNCTION]);
            elseif(isset($operation[3])) $conjunction = MySQLParser::Conjunction($operation[3]);
            if($column !== false && $value !== false && $operator !== false && $conjunction !== false)
            {
                $return = array();
                $i = 0;
                $key = ":".trim($column, Setup::ESCAPE_NAME)."Where";
                $new_key = $key;
                while(in_array($new_key, Where::$keys))
                {
                    $new_key = $key.$i;
                    ++$i;
                }
                $key = $new_key;
                Where::$keys[] = $key;
                $return[self::VALUES] = array($key => $value);
                if($first) $return[self::QUERY] = "$column $operator $key";
                else $return[self::QUERY] = " $conjunction $column $operator $key";
            }
            else
            {
                ErrorCollection::addErrorInfo(self::ERROR_INFO(__FUNCTION__), "Could not make where from given value!", $operation);
            }
        }

        return $return;
    }
    /**
     * Function MAKEFromArrays
     * for making where query data from array full of arrays.
     * @param array $operations Operations for where query.
     * @return array|bool Query data if successful else false.
     */
    private static function MAKEFromArrays($operations)
    {
        $return = false;
        $query = "";
        $values = array();
        $first = true;
        foreach($operations as $operation)
        {
            $data = self::MAKEFromArray($operation, $first);
            if($first)$first = false;
            if(Checker::isArray($data, false) && isset($data[self::QUERY]) && isset($data[self::VALUES]))
            {
                $query .= $data[self::QUERY];
                $values += $data[self::VALUES];
            }
            else
            {
                ErrorCollection::addErrorInfo(self::ERROR_INFO(__FUNCTION__), "Given data was not correct array!", $data);
            }
        }
        if(count($values) === count($operations))
        {
            $return[self::QUERY] = $query;
            $return[self::VALUES] = $values;
        }
        return $return;
    }
}