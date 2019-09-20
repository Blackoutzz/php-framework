<?php
namespace core\frontend\components;
use core\common\str;
use core\program;
/**
 * CSRF
 *
 * Security/CSRF - Simple CSRF Protection
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class csrf 
{

    public function __construct()
    {

    }

    static public function generate_token($psalt = "")
    {
        if(isset(program::$user) && program::$user->is_connected())
        {
            return program::$cryptography->hash(str::get_safe_random(32).runid.$psalt.program::$user->get_password().$psalt.uniqid().sstr::get_safe_random(32));
        } else {
            return program::$cryptography->hash(str::get_safe_random(32).runid.$psalt.uniqid().$psalt.runid.str::get_safe_random(32));
        }
    }

    static public function set_token()
    {
        if(!isset($_SESSION)) program::start_session();
        if(!isset($_SESSION["user"]) && !is_array($_SESSION["user"])) $_SESSION["user"] = array();
        $_SESSION["user"]["token"] = self::generate_token();
    }

}
