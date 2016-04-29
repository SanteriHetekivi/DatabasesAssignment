<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.40
 */

/**
 * Class App
 * for Databases
 */
class App extends AppRoot
{
    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->FILE = __FILE__;
    }

    /**
     * Function TEST
     * for testing.
     * @return array|bool|string test result.
     */
    public function TEST()
    {
        /*$where = array(
            array("column" => "aas", "operator" => "<=", "value" => 6),
            array("column" => "asa", "value" => 0, "conjunction" => "OR")
        );*/
        //$return = array();
        //$columns = "*";
        //$table = "testi";
        //$where = false;
        //$where = array("nimi" => "testila");
        //$order = array("hinta"=>"DESC");
        //$limit = 1;
        //$query = new MySQLQuery();
        //$ok = $query->setSelect($columns, $table, $where, $order, $limit);
        /*if($ok)
        {
            $return = $this->MySQL()->CALL($query);
        }*/


        //$query = new MySQLQuery();
        //$query->setUpdate("testi", array("nimi"=>"lol", "hinta"=> 600), array("testiID"=>1));
        //$this->MySQL()->CALL($query);
        $testi = new Testi(15);
        $return = $testi->Values();



        DATA::setSuccess(Checker::isArray($return));
        return $return;
    }

    protected function AUTHENTICATE()
    {
        return true;
    }

    public function Rights($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        if($object && $id && Checker::isObject($object, "User"))
        {
            /** @var User $object */
            $object->setID($id);
            if($object->SELECT())
            {
                $rights = $object->Rights();
                if(Checker::isArray($rights))
                {
                    $return = $object->Rights();
                    DATA::setSuccess(true);
                }
            }
        }
        return $return;
    }

    public function AddUserToGroup($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        $values = $this->getValues($pars);
        $userGroupId = (Checker::isArray($values) && isset($values["userGroupId"]))?Parser::Int($values["userGroupId"]):false;
        if($object && $id && Checker::isObject($object, "User") && $userGroupId)
        {
            /** @var User $object */
            $object->setID($id);
            if($object->SELECT())
            {
                DATA::setSuccess($object->setUserGroup($userGroupId));
            }
        }
        return $return;
    }

    public function AddItemToDemo($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = array();
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        $values = $this->getValues($pars);
        $item = (Checker::isArray($values) && isset($values["item"]))?Parser::Int($values["item"]):false;
        if($object && $id && Checker::isObject($object, "Demo", false, $errorInfo) && $item)
        {
            /** @var Demo $object */
            $object->setID($id);
            if($object->SELECT())
            {
                DATA::setSuccess($object->setItem($item));
            }
        }
        return $return;
    }

    public function Items($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return  = false;
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        if($object && $id && Checker::isObject($object, "Demo", false, $errorInfo))
        {
            /** @var Demo $object */
            $object->setID($id);
            if($object->SELECT())
            {
                $items = $object->Items();
                if(Checker::isArray($items))
                {
                    $return = array();
                    foreach($items as $item)
                    {
                        /** @var Item $item */
                        $return[$item->ID()] = $item->Values(true);
                    }
                }

            }
        }
        DATA::setSuccess(Checker::isArray($return));
        return $return;
    }

    public function makeBooking($pars)
    {
        $success = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        $values = $this->getValues($pars);
        if($object && $id && Checker::isArray($values, false, $errorInfo))
        {
            if(Checker::isObject($object, false, "MYSQLObject", $errorInfo) && $object->setID($id) && $object->SELECT())
            {
                $className = get_class($object);
                $valName = false;
                if($className == "Item")
                {
                    $valName = "booker";
                    /** @var User $object */
                }
                elseif($className == "User")
                {
                    $valName = "item";
                    /** @var Item $object */
                }
                if($valName)
                {
                    $value = (isset($values[$valName]))?Parser::Int($values[$valName]):false;
                    $club = (isset($values["club"]))?Parser::Int($values["club"]):false;
                    $timeStart = (isset($values["timeStart"]))?Parser::Time($values["timeStart"]):false;
                    $timeEnd = (isset($values["timeEnd"]))?Parser::Time($values["timeEnd"]):false;
                    $success = $object->Book($value,$club,$timeStart,$timeEnd);
                }
            }
        }
        DATA::setSuccess($success);
        return $success;
    }

    public function makeBorrow($pars)
    {
        $success = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        $values = $this->getValues($pars);
        if($object && $id && Checker::isArray($values, false, $errorInfo))
        {
            if(Checker::isObject($object, false, "MYSQLObject", $errorInfo) && $object->setID($id) && $object->SELECT())
            {
                $className = get_class($object);
                $valName = false;
                if($className == "Item")
                {
                    $valName = "borrower";
                    /** @var User $object */
                }
                elseif($className == "User")
                {
                    $valName = "item";
                    /** @var Item $object */
                }
                if($valName)
                {
                    $value = (isset($values[$valName]))?Parser::Int($values[$valName]):false;
                    $checker = (isset($values["checker"]))?Parser::Int($values["checker"]):false;
                    $book = (isset($values["book"]))?Parser::Int($values["book"]):false;
                    $timeDeadline = (isset($values["timeDeadline"]))?Parser::Time($values["timeDeadline"]):false;
                    $success = $object->Borrow($value,$checker,$book,$timeDeadline);
                }
            }
        }
        DATA::setSuccess($success);
        return $success;
    }

    public function Borrows($pars)
    {
        $return = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        $values = $this->getValues($pars);
        if($object && $id )
        {
            if(Checker::isObject($object, false, "MYSQLObject", $errorInfo) && $object->setID($id) && $object->SELECT())
            {
                if(method_exists($object, "Borrows"))
                {
                    $returnStatus = RETURN_STATUS::getReturnStatus($values);
                    $startTime = (isset($values["startTime"]))?Parser::Time($values["startTime"]):0;
                    $endTime = (isset($values["endTime"]))?Parser::Time($values["endTime"]):PHP_INT_MAX;
                    $borrows = $object->Borrows($returnStatus, $startTime, $endTime);
                    if(Checker::isArray($borrows))
                    {
                        $return = array();
                        foreach($borrows as $borrow)
                        {
                            /** @var Borrow $borrow */
                            $return[$borrow->ID()] = $borrow->Values(true);
                        }
                    }
                }
            }
        }
        DATA::setSuccess(Checker::isArray($return));
        return $return;
    }

    public function Bookings($pars)
    {
        $return = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        $values = $this->getValues($pars);
        if($object && $id )
        {
            if(Checker::isObject($object, false, "MYSQLObject", $errorInfo) && $object->setID($id) && $object->SELECT())
            {
                if(method_exists($object, "Bookings"))
                {
                    $startTime = (isset($values["startTime"]))?Parser::Time($values["startTime"]):0;
                    $endTime = (isset($values["endTime"]))?Parser::Time($values["endTime"]):PHP_INT_MAX;
                    $bookings = $object->Bookings($startTime, $endTime);
                    if(Checker::isArray($bookings))
                    {
                        $return = array();
                        foreach($bookings as $booking)
                        {
                            /** @var Book $booking */
                            $return[$booking->ID()] = $booking->Values(true);
                        }
                    }

                }
            }
        }
        DATA::setSuccess(Checker::isArray($return));
        return $return;
    }

    public function BorrowedRightNow()
    {
        $return = false;
        $borrow = new Borrow();
        $borrows = $borrow->Borrows(false, false, RETURN_STATUS::NOT_RETURNED, 0, PHP_INT_MAX);
        if(Checker::isArray($borrows))
        {
            $return = array();
            foreach($borrows as $borrow)
            {
                /** @var Borrow $borrow */
                if($borrow->isReturned() === false) $return[$borrow->ID()] = $borrow->Values(true);
            }
        }
        DATA::setSuccess(Checker::isArray($return));
        return $return;
    }

    public function BookedRightNow()
    {
        $return = false;
        $book = new Book();
        $bookings = $book->Bookings(false, false, Tools::TimeNow(), PHP_INT_MAX);
        if(Checker::isArray($bookings))
        {
            $return = array();
            foreach($bookings as $booking)
            {
                /** @var Book $booking */
                $return[$booking->ID()] = $booking->Values(true);
            }
        }
        DATA::setSuccess(Checker::isArray($return));
        return $return;
    }

    public function LateBorrows()
    {
        $return = false;
        $borrow = new Borrow();
        $borrows = $borrow->Borrows(false, false, RETURN_STATUS::NOT_RETURNED, 0, PHP_INT_MAX);
        if(Checker::isArray($borrows))
        {
            $return = array();
            foreach($borrows as $borrow)
            {
                /** @var Borrow $borrow */
                if($borrow->isLate()) $return[$borrow->ID()] = $borrow->Values(true);
            }
        }
        DATA::setSuccess(Checker::isArray($return));
        return $return;
    }

    public function GetDemoInfo($pars)
    {
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $return = array();
        $object = $this->getObject($pars);
        $id = $this->getId($pars);
        if ($object && $id && Checker::isObject($object, "Demo", false, $errorInfo) ) {
            /** @var Demo $object */
            $object->setID($id);
            if ($object->SELECT()) {
                $return["info"] = $object->Value("description");
                $items = $object->Items();
                if(Checker::isArray($items))
                {
                    $return["items"] = array();
                    foreach($items as $item)
                    {
                        /** @var Item $item */
                        $return["items"][$item->ID()] = $item->Values(true);
                    }
                }
                DATA::setSuccess(true);
            }
        }
        return $return;
    }


}