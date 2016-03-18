<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 18.3.2016
 * Time: 12.00
 */

/**
 * Class MessageCollection
 * for storing and handling messages.
 */
class MessageCollection
{
    /**
     * @var array of Message classes.
     */
    private static $messages = array();

    /**
     * Function Messages
     * for getting all messages.
     * @return array Messages array.
     */
    public static function Messages()
    {
        return MessageCollection::$messages;
    }

    /**
     * Function setMessages
     * for setting messages array.
     * @param array $messages Message array to set as messages.
     * @return bool Success of the function.
     */
    public static function setMessages($messages)
    {
        $success = false;
        $old_messages = MessageCollection::Messages();
        if(Checker::isArray($messages))
        {
            MessageCollection::$messages = array();
            foreach($messages as $message)
            {
                $success = MessageCollection::addMessageObject($message);
                if($success === false) break;
            }
        }
        else
        {
            MessageCollection::addError(__FUNCTION__, "Given message where not array", $messages);
        }

        if($success === false)
        {
            MessageCollection::$messages = $old_messages;
        }
        return $success;
    }

    /**
     * Function hasMessages
     * for checking if MessageCollection has messages.
     * @return bool Does MessageCollection have messages.
     */
    public static function hasMessages()
    {
        return Checker::isArray(MessageCollection::Messages(), false);
    }

    /**
     * Function MessagesData
     * for getting data array with data from
     * every message.
     * @return array Messages data.
     */
    public static function MessagesData()
    {
        $return = array();
        if(MessageCollection::hasMessages())
        {
            foreach(MessageCollection::Messages() as $message)
            {
                $return[] = $message->GET();
            }
        }
        return $return;

    }
    /**
     * Function addMessage
     * for adding message from values.
     * @param string $type Message type.
     * @param string $html HTML content of the message.
     * @return bool Success of the function.
     */
    public static function addMessage($type, $html)
    {
        $success = false;
        if(Checker::isString($type) && Checker::isString($html))
        {
            MessageCollection::$messages[] = new Message($type, $html);
            $success = true;
        }
        else
        {
            MessageCollection::addError(__FUNCTION__,
                "All message values needs to be strings!",
                array($type, $html)
            );
        }
        return $success;
    }

    /**
     * Function addMessageObject
     * for adding message from Message object.
     * @param Message $message Message object to add.
     * @return bool Success of the function.
     */
    public static function addMessageObject($message)
    {
        $success = true;
        if(Checker::isObject($message, "Message"))
        {
            MessageCollection::$messages[] = $message;
            $success = true;
        }
        return $success;
    }

    /**
     * Function addMessages
     * for adding array of messages.
     * @param array $messages Array of Message classes.
     * @return bool Success of the function.
     */
    public static function addMessages($messages)
    {
        $success = false;
        $old_messages = MessageCollection::Messages();
        if(Checker::isArray($messages))
        {
            $success = true;
            foreach($messages as $message)
            {
                $success = MessageCollection::addMessageObject($message);
                if($success === false) break;
            }
        }
        else
        {
            MessageCollection::addError(__FILE__, __FUNCTION__, "Given message where not array");
        }

        if($success === false)
        {
            MessageCollection::setMessages($old_messages);
        }
        return $success;
    }
    /**
     * Function addError
     * for adding error to ErrorCollection.
     * @param string $func String of the error function.
     * @param string $message String of the error message.
     * @param object|string $variable Any object or variable.
     * @return bool Success of the function.
     */
    private static function addError($func = "", $message = "", $variable = "")
    {
        return ErrorCollection::addError(__FILE__, $func, $message, $variable);
    }
}
