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

}