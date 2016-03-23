<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 23.3.2016
 * Time: 18.58
 */
class Projekti extends MySQLObject
{
    protected function INITIALIZE()
    {
        $this->setTable("Projekti");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "id"),
            new MySQLColumn("ProjektiNimi", "", "VARCHAR"),
            new MySQLColumn("Projektipaallikko", 0, "id", new Tyontekija()),
        );
        $this->setColumns($columns);
    }
}