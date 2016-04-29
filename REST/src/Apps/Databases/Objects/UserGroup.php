<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */

abstract class RIGHTS
{
    const BOOKER    = "booker";
    const BORROWER  = "borrower";
    const RETURNER  = "returner";
    const CHECKER   = "checker";
    const LEADER    = "leader";
    const DEMOER    = "demoer";
}


class UserGroup extends MySQLObject
{
    const ALL_RIGHTS = array("booker", "borrower", "returner", "checker", "leader", "demoer");

    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("userGroup");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("name", "", "VARCHAR"),
            new MySQLColumn("booker", false, "BOOL"),
            new MySQLColumn("borrower", false, "BOOL"),
            new MySQLColumn("returner", false, "BOOL"),
            new MySQLColumn("checker", false, "BOOL"),
            new MySQLColumn("leader", false, "BOOL"),
            new MySQLColumn("demoer", false, "BOOL"),
        );
        $this->setColumns($columns);
    }

}