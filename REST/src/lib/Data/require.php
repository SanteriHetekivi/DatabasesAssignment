<?php
/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 12.3.2016
 * Time: 12.24
 */

/**
 * Require file for directory /libs/Data
 */

/**
 * Filenames to require in this directory.
 */
$files = array(
    "Err",
    "Message",
    "Data",
);

/**
 * Requiring filenames from this directory.
 */
if(empty($files) === false)
{
    foreach($files as $file)
    {
        require __DIR__ . "/" . $file . ".php";
    }
}

/**
 * Directories to require in this directory.
 */
$directories = array(
);

/**
 * Requiring directories from this directory.
 */
if(empty($directories) === false)
{
    foreach($directories as $directory)
    {
        require __DIR__ . "/" . $directory . "/require.php";
    }
}