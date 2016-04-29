<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class Book extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("book");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("item", 0, "ID", new Item()),
            new MySQLColumn("booker", 0, "ID", new User()),
            new MySQLColumn("club", 0, "ID", new Club()),
            new MySQLColumn("timeStart", 0, "DATETIME"),
            new MySQLColumn("timeEnd", 0, "DATETIME"),
        );
        $this->setColumns($columns);
    }


    /**
     * Function MAKE
     * for making new booking.
     * @param int|Item $item Item to book.
     * @param int|User $booker Booker.
     * @param int|Club $club Club that books.
     * @param int|string $timeStart Booking starts.
     * @param int|string $timeEnd Booking ends.
     * @return bool Success of make.
     */
    public function MAKE($item, $booker, $club, $timeStart, $timeEnd)
    {
        $success = false;
        if(!Checker::isInt($timeStart)) $timeStart = Parser::Time($timeStart);
        if(!Checker::isInt($timeEnd)) $timeEnd = Parser::Time($timeEnd);
        if($this->canBeBooked($booker, $item, $timeStart, $timeEnd))
        {
            if(Checker::isObject($item, "Item")) $item = $item->ID();
            if(Checker::isObject($booker, "User")) $booker = $booker->ID();
            if(Checker::isObject($club, "Club")) $club = $club->ID();
            $errorInfo = Book::ERROR_INFO(__FUNCTION__);
            if(MySQLChecker::isId($item, $errorInfo) && MySQLChecker::isId($booker, $errorInfo)
                && MySQLChecker::isId($club, $errorInfo) && Checker::isInt($timeStart, true, false, $errorInfo)
                && Checker::isInt($timeEnd, true, false, $errorInfo))
            {
                $values = array(
                    "item" => $item,
                    "booker" => $booker,
                    "club" => $club,
                    "timeStart" => $timeStart,
                    "timeEnd" => $timeEnd
                );
                return $this->setValues($values);
            }
        }
        return $success;
    }

    /**
     * Function itemBookings
     * for getting all borrows for given item.
     * @param int|Item $item Item object or id to seek borrows for.
     * @param int $startTime Minimum limiter for startTime.(Optional)
     * @param int $endTime  Maximum limiter for endTime. (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function itemBookings($item, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        if(Checker::isObject($item, "Item")) $item = $item->ID();
        $obs = $this->Bookings("item", $item, $startTime, $endTime);
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return = $obs;
        }
        return $return;
    }

    /**
     * Function bookerBookings
     * for getting all borrows for given booker user.
     * @param int|User $booker User object or id to seek borrows for.
     * @param int $startTime Minimum limiter for startTime.(Optional)
     * @param int $endTime  Maximum limiter for endTime. (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function bookerBookings($booker, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        if(Checker::isObject($booker, "User")) $booker = $booker->ID();
        $obs = $this->Bookings("booker", $booker, $startTime, $endTime);
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return = $obs;
        }
        return $return;
    }

    /**
     * Function clubBookings
     * for getting all borrows for given club.
     * @param int|Club $club Club object or id to seek borrows for.
     * @param int $startTime Minimum limiter for startTime.(Optional)
     * @param int $endTime  Maximum limiter for endTime. (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function clubBookings($club, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        if(Checker::isObject($club, "Club")) $club = $club->ID();
        $obs = $this->Bookings("club", $club, $startTime, $endTime);
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return = $obs;
        }
        return $return;
    }

    /**
     * Function Bookings
     * for getting bookings for given column id pair.
     * @param String|bool $column Column for id.
     * @param int|bool $id Id for column.
     * @param int $startTime Minimum limiter for startTime.(Optional)
     * @param int $endTime  Maximum limiter for endTime. (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function Bookings($column = false, $id = false, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $startTime = Parser::DATETIME($startTime);
        $endTime = Parser::DATETIME($endTime);

        if(MySQLChecker::isDATETIME($startTime, $errorInfo) && MySQLChecker::isDATETIME($endTime, $errorInfo))
        {
            $where = array(
                array("timeStart", $endTime , "<="),
                array("timeEnd", $startTime, ">=")
            );
            if($column !== false || $id !== false)
            {
                if(Checker::isString($column) && MySQLChecker::isId($id, $errorInfo))
                {
                    $where[] = array($column, $id);
                }
                else return false;
            }
            $obs = $this->GetAll($where);
            if(Checker::isArray($obs, true, $errorInfo))
            {
                $return = $obs;
            }
        }
        return $return;
    }

    protected function beforeCOMMIT()
    {
        $success =  parent::beforeCOMMIT();
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $start = $this->Value("timeStart");
        $end = $this->Value("timeEnd");
        if($start >= $end)
        {
            ErrorCollection::addErrorInfo($errorInfo, "Start was after or on end!", array("start" => $start, "end" => $end));
            $success = false;
        }
        $success = $this->canBeBooked() && $success;

        return $success;
    }

    public function canBeBooked($booker = false, $item = false, $timeStart = false, $timeEnd = false)
    {
        if($item === false)         $item       = $this->Value("item");
        if($booker === false)       $booker     = $this->Value("booker");
        if($timeStart === false)    $timeStart  = $this->Value("timeStart");
        if($timeEnd === false)      $timeEnd    = $this->Value("timeEnd");
        $errorInfo  = $this->ERROR_INFO(__FUNCTION__);

        if(!Checker::isObject($booker, "User"))
        {
            if(MySQLChecker::isID($booker))
            {
                $booker = new User($booker);
                $booker->SELECT();
            }
            else
            {
                $this->addError(__FUNCTION__, "Given booker was not User object or id!", $booker);
                return false;
            }
        }
        /** @var User $booker */
        if(!Checker::isObject($item, "Item"))
        {
            if(MySQLChecker::isID($item))
            {
                $item = new Item($item);
                $item->SELECT();
            }
            else
            {
                $this->addError(__FUNCTION__, "Given item was not Item object or id!", $item);
                return false;
            }
        }
        /** @var Item $item */
        $success = true;
        if($booker->isBooker() === false)
        {
            $this->ErrorColumn("booker", "User ".$booker->Name()." has no right to book!");
            $success = false;
        }
        $bookings = $item->Bookings($timeStart, $timeEnd);
        $borrows = $item->Borrows($timeStart, $timeEnd);

        $hasBorrows = Checker::isArray($borrows, false);
        $hasBookings = Checker::isArray($bookings, false);

        if(count($bookings) === 1 && Checker::isObject(end($bookings), "Book", false, $errorInfo) &&
            end($bookings)->ID() == $this->ID()) $hasBookings = false;

        if($hasBookings || $hasBorrows)
        {
            if($hasBookings && $hasBorrows) $type = "bookings and borrows";
            elseif($hasBookings) $type = "bookings";
            elseif($hasBookings) $type = "borrows";
            $this->ErrorColumn("timeStart", "There is $type at given time span for the item.");
            $this->ErrorColumn("timeEnd", "There is $type at given time span for the item.");
            $success = false;
        }

        return $success;
    }
}