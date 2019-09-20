<?php
namespace core\programs;
use core\program;
use core\common\exception;
use core\program_runtime_type;

/**
 * MVC
 *
 * Define the backend of the main app using an MVC Structure
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class mvc extends program
{

    public function __construct($pargv = array())
    {
        try
        {
            $this->runtime(program_runtime_type::prod);
            if($this->start_session())
            {
                $this->configure($pargv);
                if(self::is_configured())
                {
                    if(self::$database->is_connected())
                    {
                        $this->load_plugins();
                        if(!self::$user->is_ban())
                        {
                            if(!$this->load_controller()) throw new exception("No controller that fit your needs has been found.");
                        }
                        else throw new exception("Silence is Golden");
                    } else {
                        http_response_code(503);
                        die("Service unavailable");
                    }
                } else {
                    if(!isset($_SESSION["installation"])) self::reset_session();
                    if(!$this->load_controller()) throw new exception(" Installation files missing.");
                }
            } else {
                throw new exception("Session error.");
            }
        }
        catch (exception $e)
        {
            die($e->get_message());
        }
    }

    static public function is_configured() : bool
    {
        return (isset(self::$database) && isset(self::$user) && isset(self::$cryptography) && self::$configured === false);
    }

    static public function is_installed() : bool
    {
        return false;
    }
    
    static public function get_option($poption)
    {
        return self::$database->get_app_option_by_option($poption);
    }

    static public function set_option($poption,$pvalue)
    {
        return self::$database->set_app_option($poption,$pvalue);
    }

}