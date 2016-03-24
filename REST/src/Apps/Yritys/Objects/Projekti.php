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
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("ProjektiNimi", "", "VARCHAR"),
            new MySQLColumn("Projektipaallikko", 0, "ID", new Tyontekija()),
        );
        $this->setColumns($columns);
    }

    public function getEmployees()
    {
        $tekijat = "Työntekijät";
        $paalikko = "Projektipaallikko";
        $return = array( $tekijat => array(), $paalikko => null);
        $tekee = new Tekee();
        $boss = $this->Value($paalikko, true);
        if(Checker::isObject($boss, "Tyontekija")) $return[$paalikko] = $boss;
        $employees = $tekee->GetEmployees($this->ID());
        if(Checker::isArray($employees)) $return[$tekijat] = $employees;
        return $return;
    }

    public function getEmployeeValues()
    {
        $tekijat = "Työntekijät";
        $paalikko = "Projektipaallikko";
        $return = array( $tekijat => array(), $paalikko => null);
        $employees = $this->getEmployees();
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if(Checker::isArray($employees, false, $errorInfo) && isset($employees[$tekijat]) &&
            Checker::isArray($employees[$tekijat], true, $errorInfo) && isset($employees[$paalikko]))
        {
            $return[$paalikko] = (Checker::isObject($employees[$paalikko], "Tyontekija"))?$employees[$paalikko]->Values(true):null;
            $tyontekijat = $employees[$tekijat];
            $employees = array();
            foreach($tyontekijat as $id => $tyontekija)
            {
                if($this->isObject($tyontekija, "Tyontekija", false, __FUNCTION__)) $employees[$id] = $tyontekija->Values(true);
            }
            $return[$tekijat] = $employees;
        }
        return $return;
    }
}