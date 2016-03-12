<?php
/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 12.24
 */
$files = array(
    "Checker",
    "MySQLChecker",
    "MySQLParser",
    "MySQL",
);

if(empty($files) === false)
{
    foreach($files as $file)
    {
        require __DIR__ . "/" . $file . ".php";
    }
}
