<?php
namespace core\programs;
use core\program;
use core\common\exception;
use core\program_runtime_type;
use core\backend\components\filesystem\file;
use core\backend\components\databases\mysql;
use core\managers\databases;
use core\managers\users;
use core\common\str;
use core\backend\components\mvc\cryptography;
use core\backend\database\mysql\datasets\user as mysql_user;

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
            $this->runtime(self::$runtime);
            $this->configure($pargv);
            if($this->start_session())
            {
                if(self::is_configured())
                {
                    phpinfo();
                } 
                die("Please rebuild the docker container");
            }
        }
        catch (exception $e)
        {
            die($e->get_message());
        }
    }

    static public function is_configured() : bool
    {
        $config = new file(self::$path."config.php",false);
        return ($config->exist());
    }
    
    static public function get_option($poption)
    {
        return self::$databases->get_mysql_database_by_id()->get_model()->get_app_option_by_option($poption);
    }

    static public function set_option($poption,$pvalue)
    {
        return self::$database->set_app_option($poption,$pvalue);
    }

    protected function configure($pargv = array())
    {
        $config = new file(self::$path."config.php",false);
        if($this->is_using_console() 
        && !$config->exist()
        && isset($_SERVER["argv"][1]) 
        && $_SERVER["argv"][1] === "docker-setup")
        {
            if(isset($_SERVER["DATABASE_HOST"]) 
            && isset($_SERVER["DATABASE_NAME"])
            && isset($_SERVER["DATABASE_PORT"])
            && isset($_SERVER["DATABASE_USER"])
            && isset($_SERVER["DATABASE_PASSWORD"]))
            {
                sleep(20);
                if($this->configure_database()
                && isset($_SERVER["ADMIN_USERNAME"]) 
                && isset($_SERVER["ADMIN_PASSWORD"])
                && isset($_SERVER["ADMIN_EMAIL"]))
                {
                    if($this->configure_cryptography())
                    {
                        $this->configure_user();
                        die();
                    }
                    die("something happend rebuild the container");
                }
            }
            die("something is missing to fully configure the container");
        }
        else
        {
            self::$debug = intval($this->is_configured());
            self::$databases = new databases();
            self::$databases[] = new mysql($pargv["db"]);
            self::$cryptography = new cryptography(["salt"=>$pargv["salt"],"algo"=>$pargv["algo"]]);
            self::$users = new users();
            //self::$users[] = new user();
            //self::$routing = new routing();
            return true;
        }
    }

    protected function configure_database()
    {
        self::$databases = new databases();
        $database_host = $_SERVER["DATABASE_HOST"];
        $database_port = $_SERVER["DATABASE_PORT"];
        $database_user = $_SERVER["DATABASE_USER"];
        $database_password = $_SERVER["DATABASE_PASSWORD"];
        $database_name = $_SERVER["DATABASE_NAME"];
        $db = '$database = array("HOST"=>"'.$database_host.'","PORT"=>"'.$database_port.'","USER"=>"'.$database_user.'","PASS"=>"'.$database_password.'","DB"=>"'.$database_name.'");';
        self::$databases[] = new mysql(["host"=>$database_host,"port"=>$database_port,"username"=>$database_user,"password"=>$database_password,"db"=>$database_name]);
        $database = self::$databases->get_mysql_database_by_id();
        $config = new file(self::$path."config.php");
        if($config->set_contents("<?php \n{$db}\n")){
            if($database->get_connection()->execute_script("/vendor/blackoutzz/framework/src/core/backend/database/mysql/scripts/install.sql"))
            {
                return true;
            } 
        }
        return false;
    }

    protected function configure_cryptography()
    {
        $salt = str::get_safe_random(64, array("\"","\\","$","'",'"'));
        if(hash_hmac('sha3-512',"test",$salt)) $algo ='sha3-512';
        elseif(hash_hmac('sha512',"test",$this->salt)) $algo ='sha512';
        elseif(hash_hmac('sha256',"test",$this->salt)) $algo ='sha256';
        elseif(hash_hmac('sha1',"test",$this->salt)) $algo ='sha1';
        else $algo ='md5';
        $crypt = '$salt = "'.$salt.'";'.CRLF.'$algo = "'.$algo.'";'.CRLF;
        $config = new file(self::$path."config.php",false);
        self::$cryptography = new cryptography(["salt"=>$salt,"algo"=>$algo]);
        return $config->add_contents($crypt);
    }

    protected function configure_user()
    {
        self::$users = new users();
        $password = self::$cryptography->hash($_SERVER["ADMIN_PASSWORD"]);
        $root = new mysql_user(array("name"=>$_SERVER["ADMIN_USERNAME"],"group"=>5,"password"=>$password,"email"=>$_SERVER["ADMIN_EMAIL"]));
        if($root->save())
        {
            return true;
        } else {
            return false;
        }
    }

}