<?php
namespace core\backend\system;
use core\common\exception;

/**
 * OS
 *
 * Helper to get informations about the OS
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class os
{

    static function get_name()
    {
        try{
            if(function_exists("php_uname"))
            {
                return php_uname("s");
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_version()
    {
        //TODO
        return "unknown";
    }

    static function get_distribution()
    {
        //TODO
        return "unknown";
    }

    static function is_windows()
    {
        if(trim(self::get_name()) == "Windows NT" || preg_match("~Windows.*~im",self::get_name()))
        {
            return true;
        }
        return false;
    }

    static function is_macos()
    {
        //TODO
        return false;
    }

    static function is_unix()
    {
        if(trim(self::get_name()) == "Windows NT" || preg_match("~Windows.*~im",self::get_name()))
        {
            return false;
        }
        return true;
    }

}