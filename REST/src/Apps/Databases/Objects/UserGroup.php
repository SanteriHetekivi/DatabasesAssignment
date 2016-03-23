<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class User extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->setTable("usergroup");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "id"),
            new MySQLColumn("username", "", "VARCHAR"),
            new MySQLColumn("password", "", "VARCHAR"),
            new MySQLColumn("firstName", "", "VARCHAR"),
            new MySQLColumn("lastName", "", "VARCHAR"),
            new MySQLColumn("email", "", "VARCHAR"),
            new MySQLColumn("removed", false, "BOOL"),
        );
        $this->setColumns($columns);
    }
}