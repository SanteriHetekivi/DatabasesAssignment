<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class Item extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("item");
        $columns = array(
            new MySQLColumn($this->IdName(), 0, "ID"),
            new MySQLColumn("name", "", "VARCHAR"),
        );
        $this->setColumns($columns);
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
        return $booking->itemBookings($this->ID(), $startTime, $endTime);
    }

    /**
     * Function Borrows
     * for getting all borrows of item.
     * @param int $returnStatus RETURN_STATUS for return type.(Optional)
     * @param int $startTime Minimum limiter for timeBorrow.(Optional)
     * @param int $endTime Maximum limiter for return or deadline times (Optional)
     * @return array|bool Array of borrows or false.
     */
    public function Borrows($returnStatus = RETURN_STATUS::ALL, $startTime = 0, $endTime = PHP_INT_MAX)
    {
        $borrow = new Borrow();
        return $borrow->itemBorrows($this->ID(), $returnStatus, $startTime, $endTime);
    }

    /**
     * Function isBooked
     * for checking if item is/was booked between times given.
     * @param int $timeStart Start time for search.
     * @param int $timeEnd End time for search.
     * @return bool Was/is item booked between times.
     */
    public function isBooked($timeStart, $timeEnd)
    {
       return Checker::isArray($this->Bookings($timeStart, $timeEnd), false);
    }

    /**
     * Function isBorrowed
     * for checking if item is/was borrowed between times given.
     * @param int $timeStart Start time for search.
     * @param int $timeEnd End time for search.
     * @param int $returnStatus RETURN_STATUS for return type.(Optional)
     * @return bool Was/is item borrowed between times.
     */
    public function isBorrowed($timeStart, $timeEnd, $returnStatus = RETURN_STATUS::NOT_RETURNED)
    {
        return Checker::isArray($this->Borrows($returnStatus, $timeStart, $timeEnd), false);
    }

    public function Borrow($borrower, $checker, $book, $timeDeadline)
    {
        $borrow = new Borrow();
        if($borrow->MAKE($this, $borrower, $checker, $book, $timeDeadline))
        {
            return $borrow->COMMIT();
        }
        return false;
    }

    public function Book($booker, $club, $timeStart, $timeEnd)
    {
        $book = new Book();
        if($book->MAKE($this, $booker, $club, $timeStart, $timeEnd))
        {
            return $book->COMMIT();
        }
        return false;
    }


}