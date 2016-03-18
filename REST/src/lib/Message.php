<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 18.3.2016
 * Time: 11.44
 */

/**
 * Class Message
 * for storing message to user.
 */
class Message
{
    /**
     * @var string Type of the message
     */
    private $type;

    /**
     * Function Type
     * for getting message's type.
     * @return string Type of the message.
     */
    public function Type()
    {
        return $this->type;
    }

    /**
     * Function setType
     * for setting message type.
     * @param string $type Message type.
     * @return bool Success of the function.
     */
    public function setType($type)
    {
        $success = false;
        if(Checker::isString($type) && in_array($type, Setup::$MESSAGE_TYPES))
        {
            $this->type = $type;
            $success = true;
        }
        else $this->addError(__FUNCTION__, "Type not supported!", $type);
        return $success;
    }

    /**
     * @var string HTML code to show as a message.
     */
    private $html;

    /**
     * Function HTML
     * for getting messages HTML.
     * @return string HTML of the message.
     */
    public function HTML()
    {
        return $this->html;
    }

    /**
     * Function setHTML
     * for setting message's HTML.
     * @param string $html HTML for the message.
     * @return bool Success of the function.
     */
    public function setHTML($html)
    {
        $success = false;
        if(Checker::isString($html))
        {
            $this->html = $html;
            $success = true;
        }
        return $success;
    }

    /**
     * Message constructor.
     * @param string $type Type of the message.
     * @param string $html Content of the message.
     */
    public function __construct($type, $html)
    {
        $this->setType($type);
        $this->setHTML($html);
    }

    /**
     * Message destructor.
     */
    public function __destruct()
    {
        unset($this->html);
        unset($this->type);
    }

    /**
     * Function GET
     * for getting message data.
     * @return array Message data.
     */
    public function GET()
    {
        return array("type" => $this->Type(), "html" => $this->HTML());
    }

    /**
     * Function addError
     * for adding error to ErrorCollection.
     * @param string $func String of the error function.
     * @param string $message String of the error message.
     * @param object|string $variable Any object or variable.
     * @return bool Success of the function.
     */
    private function addError($func = "", $message = "", $variable = "")
    {
        return ErrorCollection::addError(__FILE__, $func, $message, $variable);
    }

}
