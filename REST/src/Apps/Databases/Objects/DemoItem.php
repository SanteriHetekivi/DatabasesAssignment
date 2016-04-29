<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class DemoItem extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("demoItem");
        $columns = array(
            new MySQLColumn("demo", 0, "ID", new Demo()),
            new MySQLColumn("item", 0, "ID", new Item()),
        );
        $this->setIdNames(array("demo", "item"));
        $this->setColumns($columns);
    }

    public function GetItems($demoId)
    {
        $return = false;
        $obs = $this->GetAll(array("demo" => $demoId));
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $items = array();
            foreach($obs as $ob)
            {
                $item = $ob->Value("item", true);
                if($this->isObject($item, "Item", false, __FUNCTION__))
                {
                    $items[$item->ID()] = $item;
                }
            }
            if(count($items) === count($obs)) $return = $items;
        }
        return $return;
    }

}