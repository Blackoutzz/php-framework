<?php
namespace core\backend\components\mvc;
use core\backend\database\dataset;
use core\backend\database\mysql\model;
use core\backend\database\mysql\datasets\user_action;
use core\backend\database\mysql\datasets\user_state;
use core\backend\database\mysql\datasets\user_group;
use core\frontend\components\csrf;
use core\common\components\regex;
use core\common\regex as static_regex;
use core\common\str;
use core\common\exception;
use core\program;

/**
 * App User
 *
 * Default user of the application.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class user extends dataset
{

    protected $id = 0;

    protected $name = "Guest"; 

    protected $password;

    protected $email;

    protected $group;

    protected $state;

    protected $nonce;

    protected $authenticated;

    public function __construct()
    {
        $this->authenticated = false;
        $this->state = new user_state(array("id"=>1,"name"=>"Disconnected"));
        $this->group = new user_group(array("id"=>2,"name"=>"Guest"));
		if(isset($_SESSION["user"])) $this->restore();
	}

    public function __toString()
    {
        return $this->name;
    }

    public function get_user_agent()
    {
        try
        {
            if(isset($_SERVER))
            {
                if(isset($_SERVER["HTTP_USER_AGENT"]))
                {
                    return str::sanitize($_SERVER["HTTP_USER_AGENT"]);
                }
            }
            return false;
        } 
        catch (exception $e)
        {
            return false;
        }
    }

    public function get_ip()
    {
        try
        {
            if(isset($_SERVER))
            {
                $ip = "0.0.0.0";
                $ipv4_regex = new regex(static_regex::ipv4);
                $ipv6_regex = new regex(static_regex::ipv6);
                if(isset($_SERVER["HTTP_X_SUCURI_CLIENTIP"])){
                    if(static_regex::is_ip($_SERVER["HTTP_X_SUCURI_CLIENTIP"])) return $ip;
                }
                if(isset($_SERVER["HTTP_X_REAL_IP"])){
                    if(static_regex::is_ip($_SERVER["HTTP_X_REAL_IP"])) return $ip;
                }
                if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                    if(static_regex::is_ip($_SERVER["HTTP_X_FORWARDED_FOR"])) return $ip;
                }
                if(isset($_SERVER["REMOTE_ADDR"])){
                    $ip = $_SERVER["REMOTE_ADDR"];
                    if($ipv4_regex->match($ip) || $ipv6_regex->match($ip)) return $ip;
                }
                throw new exception("Invalid IP received from client");
            }
            throw new exception("No IP received from client");
        } 
        catch (exception $e)
        {
            return "0.0.0.0";
        }
    }

    public static function get_location()
    {
        return "Unknown";
    }

    public static function get_currency()
    {
        return "Unknown";
    }

	public function is_connected()
    {
        try 
        {
            if($this->authenticated === true) return true;
            if($this->authenticated === false) return false;
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
	}

    protected function restore()
    {
        try
        {
            if(isset($_SESSION["user"]["id"]))
            {
                $logged_user_data = model::get_user_by_id($_SESSION["user"]["id"]);
                if(model::is_user($logged_user_data))
                {
                    $this->parse_data($logged_user_data);
                    if(!isset($_SESSION["user"]) || !is_array($_SESSION["user"])) $_SESSION["user"] = array();
                    if(isset($_SESSION["user"]["token"]))
                    {
                        $this->nonce = $_SESSION["user"]["token"];
                    } else {
                        $this->nonce = csrf::generate_token();
				        $_SESSION["user"]["token"] = $this->nonce;
			        }
                    $this->authenticated = true;
                    return true;
                } else {
                    throw new exception("Invalid or deleted user still logged in.");
                }
            } else {
                throw new exception("No session detected");
            }
        }
        catch (exception $e)
        {
            $this->authenticated = false;
            return false;
        }
    }

    public function do_action($paction)
    {
        $action = 0;
        try
        {
            if(model::is_action($paction))
            {
                $action = $paction->get_id();
            } 
            elseif (is_string($paction))
            {
                $actions = model::get_actions_by_name($paction);
                if(count($actions) >= 1)
                {
                    $action = $actions[0]->get_id();
                }
            } 
            elseif (is_int($paction) || is_integer($paction))
            {
                $actions = model::get_actions_by_id($paction);
                if(count($actions) >= 1)
                {
                    $action = $actions[0]->get_id();
                }
            }
            $pdata = array(
                "id"=>0,
                "user"=>$this->id,
                "ip"=>$this->ip,
                "action"=>$action
            );
            $new_user_action = new user_action($pdata);
            if($new_user_action->save())
            {
                return $new_user_action;
            } else {
                throw new exception("Failed to execute action as user");
            }
        }
        catch (exception $e)
        {
            return new user_action(array("id"=>0,"user"=>$this->id,"ip"=>$this->ip,"action"=>$action));
        }
    }

	public function can($ppermission)
    {
        try
        {
            $group_can = model::get_user_group_permission_by_user_group_and_permission($this->group,$ppermission);
            if(model::is_user_group_permission($group_can))
            {
                if($group_can->get_granted()) return true;
            }
            $user_can = model::get_user_permission_by_user_and_permission($this->id,$ppermission);
            if(model::is_user_permission($user_can))
            {
                if($user_can->get_granted()) return true;
            }
            throw new exception("{$this->username} cannot ".$ppermission);
        }
        catch (exception $e) 
        {
            return false;
        }
	}

    public function has_access()
    {
        return true;
        try
        {
            $controller_view = program::$routing->get_controller_view();
            if($this->id >= 1)
            {
                if($user_access = model::get_user_controller_view_by_user_and_controller_view($this->id,$controller_view))
                {
                    if(model::is_user_controller_view($user_access))
                    {
                        if($user_access->get_granted()) return true;
                    }
                }
            }
            if($group_access = model::get_user_group_controller_view_by_user_group_and_controller_view($this->group,$controller_view))
            {
                if(model::is_user_group_controller_view($group_access))
                {
                    if($group_access->get_granted()) return true;
                }
            }
            if(model::get_permission_controller_view_by_controller_view_user_and_group_and_granted($controller_view,$this->id,$this->group))
            {
                return true;
            }

            $controller_name = $this->get_controller_name();
            $view_name = $this->get_view_name();
            if($controller_name === "install" && $view_name === "index" && !program::is_configured()) return true;
            throw new exception("Permission denied to access {$controller_name}/{$view_name}");
        }
        catch (exception $e)
        {
            return false;
        }
    }

	public function login($username,$password)
    {
        try
        {
            if(isset($username) && isset($password))
            {
                $password = program::$cryptography->hash($password);
                if($users_found = model::get_user_by_name($username))
                {
                    $new_user = $users_found;
                    if($new_user->get_password() == $password)
                    {
                        $this->authenticated = true;
                        $this->id = $new_user->get_id();
                        $this->name = $new_user->get_name();
                        $this->password = $new_user->get_password();
                        $this->email = $new_user->get_email();
                        $this->group = $new_user->get_group();
                        $this->status = new user_state(array("id"=>2,"name"=>"Connected"));
                        if(!isset($_SESSION["user"]) || !is_array($_SESSION["user"])) $_SESSION["user"] = array();
                        $_SESSION["user"]["id"] = $this->id;
                        $this->do_action("login");
                        $new_user->save();
                        return true;
                    } else {
                        throw new exception("Password not matching for {$username}");
                    }
                } else {
                    throw new exception("{$username} not found as valid username.");
                }
            }
            return false;
        }
        catch (exception $e) 
        {
            return false;
        }

	}

    public function logoff()
    {
        $_SESSION = array();
    }

    public function is_banned()
    {
        if(model::is_user_state($this->state))
        {
            if($this->state->get_name() != "Banned")
                return false;
        }
        return false;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function get_permissions()
    {
        return model::get_user_permissions_by_user($this->id);
    }

    public function get_group_permissions()
    {
        return $this->group->get_permissions();
    }

    public function get_controller_views()
    {
        return model::get_user_controller_views_by_user($this->id);
    }

    public function get_group_controller_views()
    {
        return $this->group->get_controller_views();
    }

	public function get_group()
    {
        return $this->group;
    }

    public function get_nonce()
    {
        return $this->nonce;
    }

    public function get_id()
    {
        return $this->id;
    }

}
