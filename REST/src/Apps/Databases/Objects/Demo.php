<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class Demo extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("demo");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("name", "", "VARCHAR"),
            new MySQLColumn("description", "", "VARCHAR"),
            new MySQLColumn("demoer", 0, "ID", new User()),
        );
        $this->setColumns($columns);
    }

    public function setItem($item)
    {
        $success = false;
        $errorInfo  = $this->ERROR_INFO(__FUNCTION__);
        $item       = Parser::MySQLObject($item, "Item", true, $errorInfo);

        if(Checker::isObject($item,"Item") && $item->SELECT())
        {
            $DemoItem = new DemoItem(array("demo"=>$this->ID(), "item"=>$item->ID()));
            $success = $DemoItem->COMMIT();
        }
        return $success;
    }

    public function Items()
    {
        $DemoItem = new DemoItem();
        return $DemoItem->GetItems($this->ID());
    }

    protected function beforeCOMMIT()
    {
        $success = parent::beforeCOMMIT();
        $demoer = $this->Value("demoer",true);
        if(Checker::isObject($demoer, "User") === false)
        {
            $this->ErrorColumn("demoer", "No demoer set!");
            $success = false;
        }
        elseif($demoer->isDemoer() === false)
        {
            $this->ErrorColumn("demoer", "User" . $demoer->Name() . " has no right to be demoer!");
            $success = false;
        }

        return $success;
    }
}