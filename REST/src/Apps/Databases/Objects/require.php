<?php
/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 16.3.2016
 * Time: 17.44
 */

/**
 * Require file for directory /src/Apps/Databases/Objects
 */

/**
 * Filenames to require in this directory.
 */
$files = array(
    "Book",
    "Borrow",
    "Club",
    "Demo",
    "DemoItem",
    "Item",
    "Membership",
    "User",
    "UserGroup",
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