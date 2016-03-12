<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 12.22
 */
class MySQL
{
    private $checker;
    private $parser;

    public function __construct()
    {
        $this->checker  = new MySQLChecker();
        $this->parser   = new MySQLParser();
    }

    public function GET($args)
    {
        $data = array();
        $sql = $this->parser->GET($args);
        if($sql)
        {
            $data["sql"] = $sql;
        }

        return $data;
    }
}