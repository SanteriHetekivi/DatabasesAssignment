<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.28
 */

/**
 * Class MySQLObject
 * for setting basic root of
 * MySQLObject.
 */
class MySQLObject extends MySQLRoot
{

    private $table;

    public function Table()
    {
        return $this->table;
    }

    protected function setTable($table)
    {
        $success = false;
        if(Checker::isString($table, false))
        {
            $this->table = $table;
            $success = true;
        }
        return $success;
    }

    private $columns;

    public function Columns()
    {
        return $this->columns;
    }

    /**
     * Function setColumn
     * for setting column to given MySQLColumn.
     * @param MySQLColumn $column Column to set.
     * @return bool Success of the function.
     */
    public function setColumn($column)
    {
        $success = false;
        if($this->isObject($column, "MySQLColumn", __FUNCTION__))
        {
            $this->columns[$column->Name()] = $column;
            $success = true;
        }
        return $success;
    }

    public function setColumns($columns)
    {
        $success = false;
        if(Checker::isArray($columns, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $success = true;
            if(empty($columns)) $this->columns = $columns;
            else
            {
                foreach($columns as $column) $success = $success && $this->setColumn($column);
            }
        }
        return $success;
    }

    public function Value($name, $linked = false)
    {
        $return = NULL;
        if(Checker::isString($name, false, $this->ERROR_INFO(__FUNCTION__)) && isset($this->columns[$name]) &&
            $this->isObject($this->columns[$name], "MySQLColumn", __FUNCTION__))
        {
            $column = $this->columns[$name];
            if($linked && $column->Linked())
            {
                $return = $column->Linked();
            }
            else $return  = $this->columns[$name]->Value();
        }
        return $return;
    }

    public function Values($linked = false)
    {
        $return = array();
        $columns = $this->Columns();
        if(Checker::isArray($columns, false))
        {
            foreach($columns as $name => $column)
            {
                if($this->isObject($column, "MySQLColumn", __FUNCTION__))
                {
                    if($linked && $column->Linked())
                    {
                        $return[$name] =  $column->Linked()->Values($linked);
                    }
                    else $return[$name] = $column->Value();
                }
            }
        }
        return $return;
    }

    public function setValue($name, $value)
    {
        $success = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if(Checker::isString($name, false, $errorInfo))
        {
            if(strtolower($name) === "id") $success = $this->setID($value);
            elseif(isset($this->columns[$name]) && $this->isObject($this->columns[$name], "MySQLColumn", __FUNCTION__))
            {
                $success = $this->columns[$name]->setValue($value);
            }
        }
        return $success;
    }

    public function setValues($values)
    {
        $success = false;
        if(Checker::isArray($values, false, $this->ERROR_INFO(__FUNCTION__)))
        {
            $success = true;
            foreach($values as $name => $value)
            {
                $success = $this->setValue($name, $value) && $success;
            }
        }
        return $success;
    }

    private $idNames;

    public function IdNames()
    {
        $return  = false;
        if(Checker::isArray($this->idNames, false)) $return = $this->idNames;
        else $return = array($this->IdName());
        return $return;
    }

    public function setIdNames($idNames)
    {
        $success = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if(Checker::isArray($idNames))
        {
            $success = true;
            foreach($idNames as $idName)
            {
                $success = Checker::isString($idName, false, $errorInfo);
            }
            if($success) $this->idNames = $idNames;
        }
        elseif(Checker::isString($idNames, false, $errorInfo))
        {
            $this->idNames = array($idNames);
            $success = true;
        }
        return $success;
    }

    public function IdName()
    {
        $return = false;
        if(isset($this->idNames[0]) && Checker::isString($this->idNames[0], false, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return  = $this->idNames[0];
        }
        else $return = MySQLParser::idName($this->Table());
        return $return;
    }

    public function ID()
    {
        return $this->Value($this->IdName());
    }

    public function IDs()
    {
        $return = false;
        $idNames = $this->IdNames();
        if(Checker::isArray($idNames, false))
        {
            $ids = array();
            foreach($idNames as $idName)
            {
                $id = $this->Value($idName);
                if(MySQLChecker::isId($id))
                {
                    $ids[$idName] = $id;
                }
            }
            if(count($idNames) === count($ids)) $return = $ids;
        }
        else
        {
            $idName = $this->IdName();
            $id = $this->Value($idName);
            if(MySQLChecker::isId($id, $this->ERROR_INFO(__FUNCTION__)))
            {
                $return = array($idName => $id);
            }
        }
        return $return;
    }

    public function setID($value)
    {
        return $this->setValue($this->IdName(), $value);
    }

    /**
     * MySQLObject constructor.
     * @param bool|int|array $values Values for class or id as int.
     */
    public function __construct($values = false)
    {
        parent::__construct();
        $this->idNames = false;
        $this->FILE = __FILE__;
        $this->INITIALIZE();
        if($values !== false)
        {
            if(MySQLChecker::isId($values))
            {
                $this->setID($values);
                $this->SELECT();
            }
            elseif(MySQLChecker::isArray($values, false))
            {
                $this->setValues($values);
                if(MySQLChecker::isId($this->ID()) === false) $this->setID(0);
                else
                {
                    $this->SELECT();
                    $this->setValues($values);
                }
            }
        }
    }

    public function SELECT()
    {
        $success = false;
        $ids = $this->IDs();
        if(MySQLChecker::isArray($ids) && $this->Connected(__FUNCTION__))
        {
            $query = new MySQLQuery();
            $queryOK = $query->setSelect("*", $this->Table(), $ids);
            if($queryOK)
            {
                $values = $this->MySQL()->CALL($query, true);
                $success = $this->setValues($values);
           }
            else
            {
                $this->addError(__FUNCTION__, "Making Select query failed!", array("*", $this->Table(), $ids));
            }
        }
        else $success = true;
        return $success;
    }

    protected function INITIALIZE()
    {

    }

    /**
     * MySQLObject destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    public function GetAll($where = false, $order = false, $limit = false)
    {
        $return = false;
        $query = new MySQLQuery();
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $queryOK = $query->setSelect($this->IdNames(), $this->Table(), $where, $order, $limit);
        if($queryOK)
        {
            $rows = $this->MySQL()->CALL($query);
            //d($rows);
            if(Checker::isArray($rows, false, $errorInfo))
            {
                $classes = array();
                $class = get_class($this);
                foreach($rows as $row)
                {
                    if(Checker::isArray($row, false, $errorInfo)) {
                        $success = true;
                        $obj = new $class();
                        foreach ($row as $idName => $id) {
                            $success = $obj->setValue($idName, $id) && $success;
                        }

                        if ($success)
                        {
                            $obj->SELECT();
                            $classes[implode("_", $row)] = $obj;
                        }
                    }
                }
                if(count($rows) === count($classes)) $return = $classes;
            }
        }
        else $this->addError(__FUNCTION__, "Query was incorrect", array($this->IdNames(), $this->Table(), $where, $order, $limit));
        return $return;
    }

    public function GetAllValues($where = false, $order = false, $limit = false)
    {
        $return = false;
        $classes = $this->GetAll($where, $order, $limit);
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if(Checker::isArray($classes, true, $errorInfo ))
        {
            $values = array();
            foreach($classes as $class)
            {
                $values[implode("_", $class->IDs())] = $class->Values(true);
            }
            if(count($classes) === count($values)) $return = $values;
        }
        return $return;
    }

}