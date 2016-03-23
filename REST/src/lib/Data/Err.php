<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.39
 */

/**
 * Class Err
 * for storing and managing error data.
 */
class Err extends Root
{

    const FILE = "file";
    const FUNC = "function";
    /**
     * @var string Filename for the error.
     */
    private $file;

    /**
     * Function File
     * for getting filename for the error.
     * @return string Filename for the error.
     */
    public function File()
    {
        return $this->file;
    }

    /**
     * Function setFile
     * for setting filename for error.
     * @param string $file Filename for the error.
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @var string Function name for the error.
     */
    private $func;

    /**
     * Function Func
     * for getting function name for the error.
     * @return string Function name for the error.
     */
    public function Func()
    {
        return $this->func;
    }

    /**
     * Function setFunc
     * for setting function name for the error.
     * @param string $func Function name for the error.
     */
    public function setFunc($func)
    {
        $this->func = $func;
    }

    /**
     * @var string Error's message..
     */
    private $message;

    /**
     * Function Message
     * for getting error's message.
     * @return string Error's message.
     */
    public function Message()
    {
        return $this->message;
    }

    /**
     * Function setMessage
     * function for setting error's message.
     * @param string $message Message for the error.
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @var object|string Variable for the error.
     */
    private $variable;

    /**
     * Function Variable
     * for getting error's variable.
     * @return object|string Error's variable.
     */
    public function Variable()
    {
        return $this->variable;
    }

    /**
     * Function setVariable.
     * for setting error's variable.
     * @param object|string $variable Variable for the error.
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;
    }

    /**
     * Err constructor.
     * @param string $file Filename for the error.
     * @param string $func Function name for the error.
     * @param string $message Message for the error.
     * @param string $variable Variable for the error.
     */
    public function __construct($file = "", $func = "", $message = "", $variable = "")
    {
        $this->setFile($file);
        $this->setFunc($func);
        $this->setMessage($message);
        $this->setVariable($variable);
    }

    /**
     * Err destructor.
     */
    public function __destruct()
    {
        unset($this->file);
        unset($this->func);
        unset($this->message);
    }

    /**
     * Function GET
     * for getting error data as array.
     * @return array of error data.
     */
    public function GET()
    {
        return array(
            "file" => $this->File(),
            "func" => $this->Func(),
            "message" => $this->Message(),
            "variable" => $this->Variable()
        );
    }
}
