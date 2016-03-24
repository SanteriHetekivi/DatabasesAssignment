<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 18.3.2016
 * Time: 15.26
 */

/**
 * Class MySQLQuery
 * for storing and making MySQL query.
 */
class MySQLQuery extends Root
{
    /**
     * Const for COLUMNS key.
     */
    const COLUMNS = "Columns";
    /**
     * Array of supported actions.
     */
    const SUPPORTED_ACTIONS = array(
        "SELECT",
        "INSERT",
        "DELETE",
        "UPDATE",
    );

    /**
     * @var bool|string Action for query.
     */
    private $action;

    /**
     * Function Action
     * for getting query's action.
     * @return bool|string Query's action or false if not set.
     */
    public function Action()
    {
        return $this->action;
    }

    /**
     * Function setAction
     * for parsing and setting Action
     * @param string $action for query.
     * @return bool Was function successful.
     */
    public function setAction($action)
    {
        $action = MySQLParser::Action($action);
        if($action) $this->action = $action;
        return (bool)($action);
    }

    /**
     * @var bool|string Columns for query.
     */
    private $columns;

    /**
     * Function Columns
     * for getting query's columns.
     * @return bool|string Query's columns or false if not set.
     */
    public function Columns()
    {
        return $this->columns;
    }

    /**
     * Function setColumns
     * for parsing and setting column.
     * @param string|array $columns Column names to set.
     * @return bool Was function successful.
     */
    public function setColumns($columns)
    {
        $columns = MySQLParser::Columns($columns);
        if($columns) $this->columns = $columns;
        return (bool)($columns);
    }

    /**
     * @var bool|string Table name for query.
     */
    private $table;

    /**
     * Function Table
     * for getting query's table.
     * @return bool|string Query's table or false if not set.
     */
    public function Table()
    {
        return $this->table;
    }

    /**
     * Function setTable
     * for parsing and setting table name.
     * @param string $table Table name to set.
     * @return bool Was function successful.
     */
    public function setTable($table)
    {
        $table = MySQLParser::Table($table);
        if($table) $this->table = $table;
        return (bool)($table);
    }

    /**
     * @var array|bool Where string and values for query.
     */
    private $where;

    /**
     * Function Where
     * for getting query's where string and values.
     * @return array|bool Query's where array or false if not set.

     */
    public function Where()
    {
        return $this->where;
    }

    /**
     * Function setWhere
     * for parsing and setting where.
     * @param string|array $where Where string or array to set.
     * @return bool Was function successful.
     */
    public function setWhere($where)
    {
        $where = Where::MAKE($where);
        if($where) $this->where = $where;
        return (bool)($where);
    }

    /**
     * @var array|bool Order string for query.
     */
    private $order;

    /**
     * Function Order
     * for getting query's order string.
     * @return string|bool Query's order or false if not set.

     */
    public function Order()
    {
        return $this->order;
    }

    /**
     * Function setOrder
     * for parsing and setting order.
     * @param string|array $order Order string or array to set.
     * @return bool Was function successful.
     */
    public function setOrder($order)
    {
        $order = OrderBy::MAKE($order);
        if($order) $this->order = $order;
        return (bool)($order);
    }

    /**
     * @var string|bool Limit for query.
     */
    private $limit;

    /**
     * Function Limit
     * for getting query's limit int.
     * @return string|bool Query's limit or false if not set.
     */
    public function Limit()
    {
        return $this->limit;
    }

    /**
     * Function setLimit
     * for parsing and setting limit.
     * @param int $limit Limit int to set.
     * @return bool Was function successful.
     */
    public function setLimit($limit)
    {
        if(Checker::isInt($limit, true, true, self::ERROR_INFO(__FUNCTION__)))
        {
            $this->limit = $limit;
            return true;
        }
        else return false;
    }
    
    /**
     * @var array|bool Values for query.
     */
    private $values;

    /**
     * Function Values
     * for getting query's values.
     * @return array|bool Query's values or false if not set.
     */
    public function Values()
    {
        return $this->values;
    }

    /**
     * Function setValues
     * for parsing and setting values.
     * @param array $values Values to set.
     * @return bool Was function successful.
     */
    public function setValues($values)
    {
        $values = MySQLParser::Values($values);
        if($values) $this->values = $values;
        return (bool)($values);
    }
    /**
     * MySQLQuery constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->action = false;
        $this->table = false;
        $this->columns = false;
        $this->where = false;
    }

    /**
     * MySQLQuery destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
        unset($this->action);
        unset($this->table);
        unset($this->columns);
        unset($this->where);
    }

    /**
     * Function Query
     * for getting MySQL query.
     * @return bool|array Query containing sql and values or false if there were error.
     */
    public function Query()
    {
        $query = false;
        $action = $this->Action();
        if($action === "SELECT")        $query = $this->Select();
        elseif($action === "INSERT")    $query = $this->Insert();
        elseif($action === "UPDATE")    $query = $this->Update();

        else $this->addError(__FUNCTION__, "Action not supported", $action);
        return $query;
    }

    /**
     * Function Select
     * for making select query.
     * @return bool|array Select query data or false if there were error.
     */
    private function Select()
    {
        $return = false;
        $action = $this->Action();
        $columns = $this->Columns();
        $table = $this->Table();
        $where = (Checker::isArray($this->Where(), false))?$this->Where():array(Where::QUERY => "", Where::VALUES => array());
        $order = (Checker::isString($this->Order()))?$this->Order():"";
        $limit = (Checker::isInt($this->Limit()))?" LIMIT ".$this->Limit():"";
        if($columns && $table && Checker::isArray($where, false))
        {
            $return[Where::QUERY] = "$action $columns FROM $table".$where[Where::QUERY]."$order$limit";

            if(Checker::isArray($where, false) && isset($where[Where::VALUES])) $return[Where::VALUES] = $where[Where::VALUES];
            else $return[Where::VALUES] = array();
        }
        return $return;
    }

    /**
     * Function Insert
     * for making insert query.
     * @return bool|array Array containing MySQL query and values array.
     */
    private function Insert()
    {
        $return = false;
        $action = $this->Action();
        $values = $this->Values();
        $table = $this->Table();
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if(MySQLChecker::isAction($action, $errorInfo) && MySQLChecker::isTable($table, $errorInfo) &&
            Checker::isArray($values, false)) {
            if (isset($values[Where::VALUES]) && isset($values[self::COLUMNS])) {
                $columns = $values[self::COLUMNS];
                $values = $values[Where::VALUES];
                if (Checker::isArray($columns, false, $errorInfo) && Checker::isArray($values, false, $errorInfo)) {
                    $return[Where::QUERY] = "$action INTO $table (" . implode(", ", array_keys($columns)) . ") VALUES (" .
                        implode(", ", $columns) . ")";
                    $return[Where::VALUES] = $values;
                }
            } else $this->addError(__FUNCTION__, "Values and Query are not set!", $values);
        }
        return $return;
    }

    /**
     * Function Update
     * for making update query.
     * @return bool|array Array containing MySQL query and values array.
     */
    private function Update()
    {
        $return = false;
        $action = $this->Action();
        $values = $this->Values();
        $table = $this->Table();
        $where = $this->Where();
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if(MySQLChecker::isAction($action, $errorInfo) && MySQLChecker::isTable($table, $errorInfo) &&
            Checker::isArray($values, false, $errorInfo) && Checker::isArray($where, false, $errorInfo) ) {
            if (isset($values[Where::VALUES]) && isset($values[self::COLUMNS]) && isset($where[Where::VALUES])) {
                $columns = $values[self::COLUMNS];
                $values = $values[Where::VALUES] + $where[Where::VALUES];
                if (Checker::isArray($columns, false, $errorInfo) && Checker::isArray($values, false, $errorInfo)) {
                    $return[Where::QUERY] = "$action $table SET ";
                    foreach($columns as $column => $key)
                    {
                        $return[Where::QUERY] .= "$column=$key, ";
                    }
                    $return[Where::QUERY] = trim($return[Where::QUERY],", ");
                    $return[Where::QUERY] .= $where[Where::QUERY];
                    $return[Where::VALUES] = $values;
                }
            } else $this->addError(__FUNCTION__, "Values and Query are not set!", $values);
        }
        return $return;
    }

    /**
     * Function setSelect
     * for setting values for Select query.
     * @param array|string $columns Columns to select.
     * @param string $table Table name
     * @param bool|string|array $where Where data for query.(Optional)
     * @param bool|string|array $order Order data for query. (Optional)
     * @param bool|int $limit Limit for query. (Optional)
     * @return bool Was setting values for select query successful.
     */
    public function setSelect($columns, $table, $where = false, $order = false, $limit = false)
    {
        $action = $this->setAction("SELECT");
        $table = $this->setTable($table);
        $columns = $this->setColumns($columns);
        $where = ($where !== false)?$this->setWhere($where):true;
        $order = ($order !== false)?$this->setOrder($order):true;
        $limit = ($limit !== false)?$this->setLimit($limit):true;

        return $action && $table && $columns && $where && $order && $limit;
    }

    /**
     * Function setInsert
     * for setting values for Select query.
     * @param string $table Table name
     * @param array $values Values for columns.
     * @return bool Was setting values for insert query successful.
     */
    public function setInsert($table, $values)
    {
        $action = $this->setAction("INSERT");
        $table = $this->setTable($table);
        $values = $this->setValues($values);
        return $action && $table && $values;
    }

    /**
     * Function setUpdate
     * for setting values for Update query.
     * @param string $table Table name
     * @param array $values Values for columns.
     * @param string|array $where Where data for query.
     * @return bool Was setting values for insert query successful.
     */
    public function setUpdate($table, $values, $where)
    {
        $action = $this->setAction("UPDATE");
        $table = $this->setTable($table);
        $values = $this->setValues($values);
        $where = $this->setWhere($where);
        return $action && $table && $values && $where;
    }
}
