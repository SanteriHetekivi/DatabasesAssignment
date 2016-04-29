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
        $this->FILE = __FILE__;
        $this->setTable("user");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("username", "", "VARCHAR"),
            new MySQLColumn("password", "", "VARCHAR"),
            new MySQLColumn("firstName", "", "VARCHAR"),
            new MySQLColumn("lastName", "", "VARCHAR"),
            new MySQLColumn("email", "", "VARCHAR"),
        );
        $this->setColumns($columns);
    }

    /**
     * Function Name
     * for getting users full name.
     * @return string Users full name.
     */
    public function Name()
    {
        return $this->Value("firstName")." ".$this->Value("lastName");
    }

    /**
     * Function UserGroups
     * for getting user groups user is a part of.
     * @return array|bool Array of UserGroup objects or false if failed.
     */
    public function UserGroups()
    {
        $membership = new Membership();
        return $membership->GetUserGroups($this->ID());
    }

    /**
     * Function Rights
     * for getting all rights that user has.
     * @return array Array of rights.
     */
    public function Rights()
    {
        $return  = array();
        $userGroups = $this->UserGroups();
        if(Checker::isArray($userGroups))
        {
            foreach(UserGroup::ALL_RIGHTS as $right)
            {
                $return[$right] = false;
                foreach($userGroups as $userGroup)
                {
                    if($this->isObject($userGroup, "UserGroup"))
                    {
                        $return[$right] = $userGroup->Value($right);
                    }
                }
            }
        }
        return $return;
    }

    /**
     * Function setUserGroup
     * for adding user to user group.
     * @param UserGroup|int $userGroup UserGroup object or id for getting UserGroup to put user to.
     * @return bool Success of set.
     */
    public function setUserGroup($userGroup)
    {
        $success = false;
        if(!Checker::isObject($userGroup, "UserGroup")) $userGroup = new UserGroup($userGroup);
        if($userGroup->SELECT())
        {
            $memberShip = new Membership(array("user" => $this->ID(), "userGroup" => $userGroup->ID()));
            $success = $memberShip->COMMIT();
        }
        return $success;
    }

    public function CheckRight($right)
    {
        $rights = $this->Rights();
        return (Checker::isArray($rights, false) && isset($rights[$right]) && $rights[$right]);
    }

    public function isBooker()
    {
        return $this->CheckRight(RIGHTS::BOOKER);
    }
    public function isBorrower()
    {
        return $this->CheckRight(RIGHTS::BORROWER);
    }
    public function isReturner()
    {
        return $this->CheckRight(RIGHTS::RETURNER);
    }
    public function isChecker()
    {
        return $this->CheckRight(RIGHTS::CHECKER);
    }
    public function isLeader()
    {
        return $this->CheckRight(RIGHTS::LEADER);
    }
    public function isDemoer()
    {
        return $this->CheckRight(RIGHTS::DEMOER);
    }

    /**
     * Function Bookings
     * for getting all bookings of item.
     * @param int $startTime Minimum limiter for startTime.(Optional)
     * @param int $endTime  Maximum limiter for endTime. (Optional)
     * @return array|bool Array of bookings or false.
     */
    public function Bookings($startTime = 0, $endTime = PHP_INT_MAX)
    {
        $booking = new Book();
        return $booking->bookerBookings($this->ID(), $startTime, $endTime);
    }

    /**
     * Function Borrows
     * for getting all borrows of item.
     * @param int $returnType RETURN_STATUS for return type.(Optional)
     * @param int $startTime Minimum limiter for timeBorrow.(Optional)
     * @param int $endTime Maximum limiter for return or deadline times (Optional)
     * @return array|bool Array of borrows or false.
     */
    public function Borrows($returnType = RETURN_STATUS::ALL, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $borrow = new Borrow();
        return $borrow->borrowerBorrows($this->ID(), $returnType, $startTime, $endTime);
    }

    public function Borrow($item, $checker, $book, $timeDeadline)
    {
        $borrow = new Borrow();
        if($borrow->MAKE($item, $this, $checker, $book, $timeDeadline))
        {
            return $borrow->COMMIT();
        }
        return false;
    }

    public function Book($item, $club, $timeStart, $timeEnd)
    {
        $book = new Book();
        if($book->MAKE($item, $this, $club, $timeStart, $timeEnd))
        {
            return $book->COMMIT();
        }
        return false;
    }


}