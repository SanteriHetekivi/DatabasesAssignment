<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 23.3.2016
 * Time: 19.00
 */
class Tekee extends MySQLObject
{
    protected function INITIALIZE()
    {
        $this->setTable("Tekee");
        $columns = array(
            new MySQLColumn("TyontekijaID", 0, "ID", new Tyontekija()),
            new MySQLColumn("ProjektiID", 0, "ID", new Projekti()),
            new MySQLColumn("Tunnit", "0", "VARCHAR"),
        );
        $this->setIdNames(array("TyontekijaID", "ProjektiID"));
        $this->setColumns($columns);
    }

    public function GetEmployees($ProjektiID)
    {
        $return = false;
        $obs = $this->GetAll(array("ProjektiID" => $ProjektiID));
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $employees = array();
            foreach($obs as $ob)
            {
                $employee = $ob->Value("TyontekijaID", true);
                if($this->isObject($employee, "Tyontekija", false, __FUNCTION__))
                {
                    $employees[$employee->ID()] = $employee;
                }
            }
            if(count($employees) === count($obs)) $return = $employees;
        }
        return $return;
    }

    public function GetEmployeeValues($ProjektiID)
    {
        $return = false;
        $employees = $this->GetEmployees($ProjektiID);
        if(Checker::isArray($employees, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $employeeValues = array();
            foreach($employees as $employee)
            {
                $employeeValues[$employee->ID()] = $employee->Values(true);
            }
            if(count($employeeValues) === count($employees)) $return = $employeeValues;
        }
        return $return;
    }
}
