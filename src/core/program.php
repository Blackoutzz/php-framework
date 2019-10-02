<?php
namespace core;
use backend\filesystem\folder;
use backend\components\databases\mysql;
use backend\components\mvc\cryptography;
use backend\components\mvc\routing;
use backend\components\mvc\user;

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

    static  $user;

    static  $cryptography;

    static  $database;

    static  $routing;

    static  $plugins = array();
    
    public function __construct($pargv = array())
    {
        self::runtime();
        self::configure($pargv);
    }

    public function __destruct()
    {
        ob_end_flush();
        http_response_code(200);
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
        ob_start();
        ini_set ("memory_limit",'1024M');
        header("X-XSS-Protection: 1");
        date_default_timezone_set('America/Montreal');
        ini_set ( "log_errors", 1 );
        if($pruntime_type === runtime_type::dev)
        {
            if(self::is_using_xdebug()) ini_set('xdebug.max_nesting_level', 30000);
            ini_set("LSAPI_MAX_PROCESS_TIME",-1);
            error_reporting(E_ERROR | E_WARNING | E_PARSE);
            ini_set ( "display_errors", 1 );
        }
        elseif ($pruntime_type === runtime_type::prod)
        {
            error_reporting(0);
            ini_set ( "display_errors", 0 );
        }

        $plugins = new folder("plugins".DS);
        $plugins = $plugins->get_folders();
        foreach($plugins as $plugin)
        {
            if(file_exists($plugin."main.php"))
            {
                if(include($plugin."main.php")) self::$plugins[$plugin->get_name()] = "\\".$plugin->get_name()."\\main";
            }
        }

        self::$cryptography = new cryptography();
        self::$user = new user();
        self::$routing = new routing();
        self::$database = new mysql();
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
        if(isset($pargv["database"])) self::$database = new mysql($pargv["database"]);
        if(isset($pargv["salt"]) && isset($pargv["algo"])) self::$cryptography = new cryptography(array("algo"=>$pargv["algo"],"salt"=>$pargv["salt"]));
        
    }

}
