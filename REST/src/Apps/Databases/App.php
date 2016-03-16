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
    }

    public function TEST()
    {
        return $this->MySQL()->SELECT("*", "testi");
    }

}