<?php
namespace core\backend\components\mvc\users;
use core\backend\components\mvc\user;
use core\backend\database\mysql\model;
use core\backend\database\mysql\dataset_array;
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

class mysql extends user
{

    public function __construct()
    {
        $this->authenticated = false;
        $this->state = new user_state(array("id"=>1,"name"=>"Disconnected"));
        $this->group = new user_group(array("id"=>2,"name"=>"Guest"));
		if(isset($_SESSION["user"])) $this->restore();
	}

    public function do_action($paction)
    {
        $action = 0;
        try{
            if(model::is_action($paction)){
                $action = $paction->get_id();
            } elseif (is_string($paction)){
                $actions = model::get_actions_by_name($paction);
                if(count($actions) >= 1){
                    $action = $actions[0]->get_id();
                }
            } elseif (is_int($paction) || is_integer($paction)){
                $actions = model::get_actions_by_id($paction);
                if(count($actions) >= 1){
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

    public function is_banned()
    {
        if(model::is_user_state($this->state))
        {
            if($this->state->get_name() != "Banned")
                return false;
        }
        return false;
    }


    public function get_permissions()
    {
        if($this->id >= 1)
            return model::get_user_permissions_by_user($this->id);
        else
            return new dataset_array();
    }

    public function get_group_permissions()
    {
        return $this->group->get_permissions();
    }

    public function get_controller_views()
    {
        if($this->id >= 1)
            return model::get_user_controller_views_by_user($this->id);
        else
            return new dataset_array();
    }

    public function get_group_controller_views()
    {
        return $this->group->get_controller_views();
    }

	public function get_group()
    {
        return $this->group;
    }

}
