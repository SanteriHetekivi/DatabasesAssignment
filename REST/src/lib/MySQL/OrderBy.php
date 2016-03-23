<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 21.3.2016
 * Time: 17.10
 */

/**
 * Class OrderBy
 * for making OrderBy data.
 */
class OrderBy
{
    /**
     * Function ERROR_INFO
     * for making ERROR_INFO data.
     * @param string $FUNCTION Name of the function.
     * @return array ERROR_INFO data.
     */
    private static function ERROR_INFO($FUNCTION){ return array(Err::FILE => __FILE__, Err::FUNC => $FUNCTION); }

    const KEYWORDS = array("ASC", "DESC");

    public static function MAKE($order, $keyword="ASC")
    {
        $return = false;
        if(Checker::isString($order))
        {
            $return = self::MAKEFromString($order, $keyword, true);
        }
        elseif(Checker::isArray($order))
        {
            $return = self::MAKEFromArray($order);
        }
        else
        {
            ErrorCollection::addErrorInfo(self::ERROR_INFO(__FUNCTION__), "Given order not supported",
                array("order"=>$order, "keyword"=>$keyword));
        }

        if(Checker::isString($return)) $return = " ORDER BY $return";
        return $return;
    }

    private static function MAKEFromString($column, $keyword="ASC", $first = true)
    {
        $return = false;
        $column = MySQLParser::Column($column);
        $keyword = MySQLParser::Order($keyword);
        $errorInfo = self::ERROR_INFO(__FUNCTION__);
        if(Checker::isString($column, false, $errorInfo) && Checker::isString($keyword, false, $errorInfo))
        {
            $separator = ($first)?"":", ";
            $return = "$separator$column $keyword";
        }
        return $return;
    }

    private static function MAKEFromArray($orders)
    {
        $return = false;
        if(Checker::isArray($orders, false))
        {
            $tmpOrders = array();
            $first = true;
            foreach($orders as $column => $keyword)
            {
                if(Checker::isString($column) === false)
                {
                    $column = $keyword;
                    $keyword = "ASC";
                }
                $tmpOrders[] = self::MAKEFromString($column, $keyword, $first);
                if($first) $first = false;
            }
            if(count($orders) === count($tmpOrders)) $return = implode("", $tmpOrders);
        }
        return $return;
    }
}