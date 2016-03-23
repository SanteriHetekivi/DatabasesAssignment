<?php
/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 16.3.2016
 * Time: 17.44
 */

/**
 * Require file for directory /src/Apps/Databases
 */

/**
 * Filenames to require in this directory.
 */
$files = array(
    "Setup",
    "PRIVATE",
    "App",
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
    "Objects"
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