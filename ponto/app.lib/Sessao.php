<?php

/**
 * Description of Seassao
 *
 * @author 80058442553
 */


 //Registry Pattern


class Sessao
{
    function  __construct()
    {
        session_start();
    }

    static function setValue($var, $value)
    {
        $_SESSION[$var] = $value;
    }

    static function getValue($var)
    {
        return $_SESSION[$var];
    }

    static function setObject($var, $object)
    {
        $_SESSION[$var] = serialize($object);
    }

    static function getObject($var)
    {
        return unserialize($_SESSION[$var]);
    }

    static function free()
    {
        $_SESSION = array();
        session_destroy();
    }
}
?>
