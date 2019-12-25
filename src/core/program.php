<?php
namespace core;
use core\backend\components\filesystem\folder;
use core\backend\components\databases\mysql;
use core\backend\components\mvc\cryptography;
use core\backend\components\mvc\routing;
use core\backend\components\mvc\user;
use core\managers\users;
use core\managers\databases;
define("runid",uniqid());
define('DS',DIRECTORY_SEPARATOR);
define('CRLF',"\r\n");

/**
 * Program
 *
 * Define the backend of the main app
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class program
{

    static  $debug;

    static  $users;

    static  $cryptography;

    static  $databases = array();

    static  $routing;

    static  $path = "./";

    static  $plugins = array();

    static  $runtime = runtime_type::prod;

    public function __construct($pargv = array())
    {
        self::runtime(self::$runtime);
        self::$databases = new databases();
        self::$cryptography = new cryptography();
        self::$users = new users();
        self::$users[] = new user();
        self::$routing = new routing();
        self::configure($pargv);
    }

    public function __destruct()
    {
        ob_end_flush();
        http_response_code(200);
    }

    static public function end($pcode = 200)
    {
        http_response_code(intval($pcode));
        self::push();
        die();
    }

    static public function abort($pcode = 500)
    {
        http_response_code(intval($pcode));
        die();
    }

    static public function disconnect() : void
    {
        ignore_user_abort(true);
        session_write_close();
        set_time_limit(0);
        while (ob_get_level() > 1) ob_end_flush();
        $last_buffer = ob_get_level();
        $length = $last_buffer ? ob_get_length() : 0;
        header("Content-Length: {$length}");
        header('Connection: close');
        if ($last_buffer) {
            ob_end_flush();
        }
        flush();
    }

    static public function runtime($pruntime_type = runtime_type::dev) : void
    {
        $local_core = new folder(self::$path."core",false);
        if($local_core->exist()) $local_core->import(true);
        ob_start();
        ini_set ("memory_limit",'1024M');
        header("X-XSS-Protection: 1");
        date_default_timezone_set('America/Montreal');
        ini_set ( "log_errors", 1 );
        if($pruntime_type === runtime_type::dev)
        {
            if(self::is_using_xdebug()) ini_set('xdebug.max_nesting_level', 30000);
            ini_set("LSAPI_MAX_PROCESS_TIME",-1);
            error_reporting(E_ALL |E_ERROR | E_WARNING | E_PARSE);
            ini_set ( "display_errors", 1 );
        }
        elseif ($pruntime_type === runtime_type::prod)
        {
            error_reporting(0);
            ini_set ( "display_errors", 0 );
        }

        $plugins = new folder(self::$path."plugins".DS,false);
        if($plugins->exist())
        {
            $plugins = $plugins->get_folders();
            if(count($plugins >=1))
            {
                foreach($plugins as $plugin)
                {
                    if(file_exists($plugin."main.php"))
                    {
                        if(include($plugin."main.php")) self::$plugins[$plugin->get_name()] = "\\".$plugin->get_name()."\\main";
                    }
                }
            }
        }
    }

    static public function push() : void
    {
        ob_flush();
        flush();
        ob_clean();
    }

    static public function pull()
    {
        return ob_get_clean();
    }

    static public function debug($pvar)
    {
        echo "<pre>";
        var_dump($pvar);
        echo"</pre>";
        die();
    }

    static public function redirect($plocation = '/')
    {
        header("Location: {$plocation}");
        http_response_code(301);
        die();
    }

    static public function start_session() : bool
    {
        session_start();
        if(session_status() == PHP_SESSION_ACTIVE) return true;
        return false;
    }

    static public function reset_session() : bool
    {
        if(session_status() == PHP_SESSION_ACTIVE)
        {
            $_SESSION = array();
            return true;
        } else {
            return self::start_session();
        }
    }
    
    static public function is_multi_threaded() : bool
    {
        return (class_exists("Thread"));
    }

    static public function is_using_console() : bool
    {
        return (php_sapi_name() == 'cli' );
    }

    static public function is_using_xdebug() : bool
    {
        return extension_loaded('xdebug');
    }

    static public function get_plugins()
    {
        return self::$plugins;
    }

    protected function configure($pargv)
    {
        if(isset($pargv["setup"])) self::$configured = $pargv["setup"];
        //if(isset($pargv["database"])) self::$databases["mysql"] = new mysql($pargv["database"]);
        if(isset($pargv["salt"]) && isset($pargv["algo"])) self::$cryptography = new cryptography(array("algo"=>$pargv["algo"],"salt"=>$pargv["salt"]));
    }

}
