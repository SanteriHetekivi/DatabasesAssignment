<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class Tyontekija extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->setTable("Tyontekija");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "id"),
            new MySQLColumn("Etunimi", "", "VARCHAR"),
            new MySQLColumn("Sukunimi", "", "VARCHAR"),
            new MySQLColumn("Palkka", "", "VARCHAR"),
            new MySQLColumn("Syntymaaika", "", "VARCHAR"),
            new MySQLColumn("Email", "", "VARCHAR"),
            new MySQLColumn("Osasto", 0, "id", new Osasto()),
        );
        $this->setColumns($columns);
    }
}