<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */

abstract class RETURN_STATUS
{
    const ALL = 0;
    const RETURNED = 1;
    const NOT_RETURNED = 2;
    public static function getReturnStatus($values)
    {
        $returnStatus = RETURN_STATUS::ALL;
        if(Checker::isArray($values,false) && isset($values["returnStatus"]) && Checker::isString($values["returnStatus"]))
        {
            $status = strtoupper($values["returnStatus"]);
            if($status === "RETURNED") $returnStatus = RETURN_STATUS::RETURNED;
            elseif($status === "NOT_RETURNED") $returnStatus = RETURN_STATUS::NOT_RETURNED;
        }
        return $returnStatus;
    }
}

class Borrow extends MySQLObject
{



    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("borrow");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("item", 0, "ID", new Item()),
            new MySQLColumn("borrower", 0, "ID", new User()),
            new MySQLColumn("returned", false, "BOOL"),
            new MySQLColumn("returner", 0, "ID", new User()),
            new MySQLColumn("checker", 0, "ID", new User()),
            new MySQLColumn("book", 0, "ID", new Book()),
            new MySQLColumn("timeBorrow", 0, "DATETIME"),
            new MySQLColumn("timeDeadline", 0, "DATETIME"),
            new MySQLColumn("timeReturn", 0, "DATETIME"),
        );
        $this->setColumns($columns);
    }

    /**
     * Function MAKE
     * for making borrow.
     * @param int|Item $item Item that was booked.
     * @param int|User $borrower Who booked item.
     * @param int|User $checker Who checked return.
     * @param int|Book $book Booking to use for borrow.
     * @param int|string $timeDeadline Deadline for borrow.
     * @return bool Success of borrow.
     */
    public function MAKE($item, $borrower, $checker, $book, $timeDeadline)
    {
        $success = false;
        if(!Checker::isInt($timeDeadline)) $timeDeadline = Parser::Time($timeDeadline);
        $timeBorrow = Tools::TimeNow();
        if($this->canBeBorrowed($item, $borrower, $checker, $book, $timeBorrow, $timeDeadline)) {
            if (Checker::isObject($item, "Item")) $item = $item->ID();
            if (Checker::isObject($borrower, "User")) $borrower = $borrower->ID();
            if (Checker::isObject($checker, "User")) $checker = $checker->ID();
            if (Checker::isObject($book, "Book")) $book = $book->ID();
            $errorInfo = Borrow::ERROR_INFO(__FUNCTION__);
            if (MySQLChecker::isId($item, $errorInfo) && MySQLChecker::isId($borrower, $errorInfo)
                && MySQLChecker::isId($book, $errorInfo) && Checker::isInt($timeDeadline, true, false, $errorInfo)
                && Checker::isInt($timeBorrow, true, false, $errorInfo)
            ) {
                $values = array(
                    "item" => $item,
                    "borrower" => $borrower,
                    "returned" => false,
                    "returner" => 0,
                    "checker" => $checker,
                    "book" => $book,
                    "timeBorrow" => $timeBorrow,
                    "timeDeadline" => $timeDeadline,
                    "timeReturn" => 0,
                );
                return $this->setValues($values) && $this->canBeBorrowed();
            }
        }
        return $success;
    }

    /**
     * Function ReturnItem
     * for returning borrowed item.
     * @param int|User $returner Who returned item.
     * @param int|User $checker Who checked return.
     * @return bool Success of return.
     */
    public function ReturnItem($returner, $checker)
    {
        $success = false;
        $timeReturn = Tools::TimeNow();
        if (Checker::isObject($returner, "User")) $returner = $returner->ID();
        if (Checker::isObject($checker, "User")) $checker = $checker->ID();
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        if (MySQLChecker::isId($returner, $errorInfo) && MySQLChecker::isId($checker, $errorInfo) &&
            Checker::isInt($timeReturn, true, false, $errorInfo))
        {
            $values = array(
                "returned" => true,
                "returner" => $returner,
                "checker" => $checker,
                "timeReturn" => $timeReturn
            );
            $this->setValues($values);
            $success = $this->COMMIT();
        }
        return $success;
    }

    /**
     * Function itemBorrows
     * for getting all borrows for given item.
     * @param int|Item $item Item object or id to seek borrows for.
     * @param int $returnType RETURN_STATUS for return type.(Optional)
     * @param int $startTime Minimum limiter for timeBorrow.(Optional)
     * @param int $endTime Maximum limiter for return or deadline times (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function itemBorrows($item, $returnType = RETURN_STATUS::ALL, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        if(Checker::isObject($item, "Item")) $item = $item->ID();
        $obs = $this->Borrows("item", $item, $returnType, $startTime, $endTime);
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return = $obs;
        }
        return $return;
    }

    /**
     * Function borrowerBorrows
     * for getting all borrows for given borrower user.
     * @param int|User $borrower User object or id to seek borrows for.
     * @param int $returnType RETURN_STATUS for return type.(Optional)
     * @param int $startTime Minimum limiter for timeBorrow.(Optional)
     * @param int $endTime  Maximum limiter for return or deadline times (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function borrowerBorrows($borrower, $returnType = RETURN_STATUS::ALL, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        if(Checker::isObject($borrower, "User")) $borrower = $borrower->ID();
        $obs = $this->Borrows("borrower", $borrower, $returnType, $startTime, $endTime);
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return = $obs;
        }
        return $return;
    }

    /**
     * Function returnerBorrows
     * for getting all borrows for given borrower user.
     * @param int|User $returner User object or id to seek borrows for.
     * @param int $returnType RETURN_STATUS for return type.(Optional)
     * @param int $startTime Minimum limiter for timeBorrow.(Optional)
     * @param int $endTime  Maximum limiter for return or deadline times (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function returnerBorrows($returner, $returnType = RETURN_STATUS::ALL, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        if(Checker::isObject($returner, "User")) $returner = $returner->ID();
        $obs = $this->Borrows("returner", $returner, $returnType, $startTime, $endTime);
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return = $obs;
        }
        return $return;
    }

    /**
     * Function checkerBorrows
     * for getting all borrows for given borrower user.
     * @param int|User $checker User object or id to seek borrows for.
     * @param int $returnType RETURN_STATUS for return type.(Optional)
     * @param int $startTime Minimum limiter for timeBorrow.(Optional)
     * @param int $endTime  Maximum limiter for return or deadline times (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function checkerBorrows($checker, $returnType = RETURN_STATUS::ALL,  $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        if(Checker::isObject($checker, "User")) $checker = $checker->ID();
        $obs = $this->Borrows("returner", $checker, $returnType, $startTime, $endTime);
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $return = $obs;
        }
        return $return;
    }

    /**
     * Function Borrows
     * for getting borrows for given column id pair.
     * @param String|bool $column Column for id.
     * @param int|bool $id Id for column.
     * @param int $returnType RETURN_STATUS for return type.(Optional)
     * @param int $startTime Minimum limiter for timeBorrow.(Optional)
     * @param int $endTime Maximum limiter for return or deadline times (Optional)
     * @return array|bool Array of borrow objects or false if failed.
     */
    public function Borrows($column = false, $id = false, $returnType = RETURN_STATUS::ALL, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $return = false;
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $startTime = Parser::DATETIME($startTime);
        $endTime = Parser::DATETIME($endTime);

        if( MySQLChecker::isDATETIME($startTime, $errorInfo) && MySQLChecker::isDATETIME($endTime, $errorInfo))
        {
            $where = array(
                array("timeBorrow", $endTime , "<="),
                array($this->endTimeColumn(), $startTime, ">=")
            );
            if($column !== false || $id !== false)
            {
                if(Checker::isString($column) && MySQLChecker::isId($id, $errorInfo))
                {
                    $where[] = array($column, $id);
                }
                else return false;
            }

            if($returnType == RETURN_STATUS::RETURNED)  $where[] = array("returned", true);
            elseif($returnType == RETURN_STATUS::NOT_RETURNED)  $where[] = array("returned", false);
            $obs = $this->GetAll($where);
            if(Checker::isArray($obs, true, $errorInfo))
            {
                $return = $obs;
            }
        }
        return $return;
    }


    private function endTimeColumn()
    {
        return ($this->isReturned())?"timeReturn":"timeDeadline";
    }

    private function endTime()
    {
         return $this->Value($this->endTimeColumn());
    }

    /**
     * Function isReturned
     * for checking if borrow has been returned.
     * @return bool
     */
    public function isReturned()
    {
        return ($this->Value("returned") === true);
    }

    /**
     * Function beforeCOMMIT
     * to do tasks and check before committing.
     * @return bool Success of tests.
     */
    protected function beforeCOMMIT()
    {
        $success =  parent::beforeCOMMIT();
        $errorInfo = $this->ERROR_INFO(__FUNCTION__);
        $timeBorrow = $this->Value("timeBorrow");
        $timeDeadline = $this->Value("timeDeadline");
        if($timeBorrow >= $timeDeadline)
        {
            $this->ErrorColumn("timeBorrow", "Borrow time was after or on deadline!");
            $success = false;
        }
        if($this->isReturned())
        {
            if($this->Value("timeReturn") <= 0) $this->Value("timeReturn", Tools::TimeNow());
            $timeReturn = $this->Value("timeReturn");
            if($timeBorrow < $timeReturn)
            {
                $this->ErrorColumn("timeReturn", "Return time was before borrow time!");
                $success = false;
            }
            $returner = $this->Value("returner");
            if($returner <= 0)
            {
                $this->ErrorColumn("returner", "No returner set!");
                $success = false;
            }
        }
        $checker = $this->Value("checker");
        if($checker <= 0)
        {
            $this->ErrorColumn("checker", "No checker set!");
            $success = false;
        }
        $borrower = $this->Value("borrower");
        if($borrower <= 0)
        {
            $this->ErrorColumn("borrower", "No borrower set!");
            $success = false;
        }
        return $success;
    }


    public function canBeBorrowed($item = false, $borrower = false, $checker = false, $book = false,
                                  $timeBorrow = false, $timeDeadline = false)
    {
        $success    = false;
        $errorInfo  = $this->ERROR_INFO(__FUNCTION__);

        if($item === false)         $item           = $this->Value("item");
        if($borrower === false)     $borrower       = $this->Value("borrower");
        if($checker === false)      $checker        = $this->Value("checker");
        if($timeBorrow === false)   $timeBorrow     = $this->Value("timeBorrow");
        if($timeDeadline === false) $timeDeadline   = $this->Value("timeDeadline");
        if($book === false)         $book           = $this->Value("book");

        $item       = Parser::MySQLObject($item, "Item", true, $errorInfo);
        $borrower   = Parser::MySQLObject($borrower, "User", true, $errorInfo);
        $checker    = Parser::MySQLObject($checker, "User", true, $errorInfo);
        $book       = Parser::MySQLObject($book, "Book", true);

        if($item && $borrower && $checker)
        {

            /** @var Item $item */
            /** @var User $borrower */
            /** @var User $checker */

            $success = true;
            if($borrower->isBorrower() === false) {
                $this->ErrorColumn("booker", "User " . $borrower->Name() . " has no right to borrow!");
                $success = false;
            }
            if($checker->isChecker() === false) {
                $this->ErrorColumn("checker", "User " . $checker->Name() . " has no right to check!");
                $success = false;
            }
            $bookings = $item->Bookings($timeBorrow, $timeDeadline);
            $hasBorrows = $item->isBorrowed($timeBorrow, $timeDeadline);
            $hasBookings = Checker::isArray($bookings, false);

            if($book)
            {
                /** @var Book $book */
                if(count($bookings) > 1) $hasBookings = true;
                elseif(Checker::isObject(end($bookings), "Book", false, $errorInfo))
                {
                    $b = end($bookings);
                    /** @var Book $b */
                    if($b->ID() !== $book->ID())
                    {
                        $this->ErrorColumn("book", "Booking given, but not found in database!");
                        $success = false;
                    }
                    // Given booking was only booking for item at that time span.
                    else $hasBookings = false;
                }
                else
                {
                    $this->ErrorColumn("book", "Booking given, but not found in database!");
                    $success = false;
                }
            }

            if($hasBookings || $hasBorrows)
            {
                if($hasBookings && $hasBorrows) $type = "bookings and borrows";
                elseif($hasBookings) $type = "bookings";
                elseif($hasBorrows) $type = "borrows";
                $this->ErrorColumn("timeStart", "There is $type at given time span for the item.");
                $this->ErrorColumn("timeEnd", "There is $type at given time span for the item.");
                $success = false;
            }
            if($timeBorrow >= $timeDeadline)
            {
                $this->ErrorColumn("timeBorrow", "Borrow time was after or on deadline!");
                $success = false;
            }
        }
        else
        {
            if(!$item)      $this->ErrorColumn("item", "Item is not given.");
            if(!$borrower)  $this->ErrorColumn("borrower", "Borrower is not given.");
            if(!$checker)   $this->ErrorColumn("checker", "Item is not given.");
            //if(!$book) $this->ErrorColumn("book", "Booking is not given.");
        }
        return $success;
    }

    public function isLate()
    {
        return ($this->isReturned() === false && $this->Value("timeDeadline") < Tools::TimeNow());
    }
}