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
}