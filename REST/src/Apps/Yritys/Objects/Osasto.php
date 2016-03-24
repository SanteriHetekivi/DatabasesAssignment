<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 23.3.2016
 * Time: 18.38
 */
class Osasto extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->setTable("Osasto");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("OsastoNimi", "", "VARCHAR"),
        );
        $this->setColumns($columns);
    }
}