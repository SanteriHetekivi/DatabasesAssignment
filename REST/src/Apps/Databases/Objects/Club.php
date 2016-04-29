<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class Club extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("club");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("name", "", "VARCHAR"),
            new MySQLColumn("leader", 0, "ID", new User()),
        );
        $this->setColumns($columns);
    }

    protected function beforeCOMMIT()
    {
        $success = parent::beforeCOMMIT();
        $leader = $this->Value("leader",true);
        if(Checker::isObject($leader, "User") === false)
        {
            $this->ErrorColumn("leader", "No leader set!");
            $success = false;
        }
        elseif($leader->isLeader() === false)
        {
            $this->ErrorColumn("leader", "User" . $leader->Name() . " has no right to be leader!");
            $success = false;
        }

        return $success;
    }
}