<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 16.3.2016
 * Time: 17.25
 */

/**
 * Class SetupRoot
 * for managing root level static
 * settings.
 */
class SetupRoot
{
    /**
     * PUBLIC STATIC GLOBAL VARIABLES
     */

    /**
     * @var string Decimeter for string that contain array.
     */
    public static $DECIMETER = ",";
    public static $MESSAGE_TYPES = array(
        "message", "error"
    );


    /**
     * FUNCTIONS AND VARIABLES FOR SETTING APP
     */

    /**
     * @var array of supported apps.
     */
    private static $apps = array(
       "Databases"
    );
    /**
     * @var string Name of the app.
     */
    private static $app;

    /**
     * Function App
     * for getting app's name
     * @return bool|string If app name is supported then name otherwise false.
     */
    public static function App()
    {
        $return = false;
        if(SetupRoot::checkApp(SetupRoot::$app))
        {
            $return =  SetupRoot::$app;
        }
        return $return;
    }

    /**
     * Function pathApp
     * for getting path for the app.
     * @return bool|string If app name is supported then path to that app otherwise false.
     */
    public static function pathApp()
    {
        $return = false;
        $app = SetupRoot::App();
        if($app)
        {
            $return = __DIR__ . "/Apps/" . $app . "/";
        }
        return $return;
    }

    /**
     * Function setApp
     * for setting app's name.
     * @param string $app Name of the app.
     * @return bool Was app's name set.
     */
    public static function setApp($app)
    {
        $success = false;
        if(SetupRoot::checkApp($app))
        {
            SetupRoot::$app = $app;
            $success = true;
        }
        return $success;
    }

    /**
     * Function checkApp
     * for checking if given name is supported app name.
     * @param string $app Name of the app.
     * @return bool Was name supported.
     */
    public static function checkApp($app)
    {
        return isset($app) && is_string($app) && in_array($app, SetupRoot::$apps);
    }
}
