<?php
namespace core\backend\components\mvc\controllers;
use core\backend\components\mvc\controller;
use core\common\exception;
use core\backend\database\mysql\model;
use core\backend\database\mysql\datasets\user;
use core\common\conversions\json;
use core\common\conversions\csv;
use core\common\conversions\xml;

/**
 * API Controller.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class api extends controller
{
    protected $connected = false;

    public function initialize()
    {
        if($this->has_view())
        {
            $data = $this->prepare_view();
            $recursive = false;
            if(isset($_REQUEST["output"]))
            {
                $output = strtolower($_REQUEST["output"]);
                if($output === "xml")
                {
                    header("Content-Type: text/xml");
                    die(xml::encode($data));
                }
                if($output === "csv")
                {
                    header("Content-Type: text/csv");
                    die(csv::encode($data));
                }
            }
            header("Content-Type: text/json");
            die(json::encode($data,true));
        }
    }

    protected function on_requirement_failed()
    {
        http_response_code(403);
        die(json_encode(array("code"=>403,"message"=>"Permission denied to access the API.")));
    }

    protected function require_login()
    {
        try
        {
            if(isset($_REQUEST["key"]))
            {
                $api_key = $_REQUEST["key"];
                $user_option = model::get_user_option_by_option_and_value("api-key",$api_key);
                if(model::is_user_option($user_option))
                {
                    if($user_option->get_value() === $api_key)
                    {
                        $this->connected = true;
                        return true;
                    }
                }
                throw new exception("API: Invalid API Key.");
            } else {
                throw new exception("API: No key found.");
            }
        }
        catch(exception $e)
        {
            $this->on_requirement_failed();
            return false;
        }
    }

    protected function is_login()
    {
        return $this->connected;
    }

    protected function get_user()
    {
        if(isset($_REQUEST["key"]))
        {
            $api_key = $_REQUEST["key"];
            $user_option = model::get_user_option_by_option_and_value("api-key",$api_key);
            if(model::is_user_option($user_option))
            {
                if($user_option->get_value() === $api_key)
                {
                    return $user_option->get_user();
                }
            }
        }
        return new user(array("id"=>0,"name"=>"Visitor","user_group"=>2));
    }

    protected function require_permission($ppermission)
    {
        try
        {
            $user = $this->get_user();
            if(is_array($ppermission))
            {
                foreach($ppermission as $permission)
                {
                    if($user->can($permission)) return true;

                }
                throw new exception("Required permissions missing for {$user}");
            } else {
                if($user->can($ppermission)) return true;
                throw new exception("{$ppermission} permission required missing for {$user}");
            }
        }
        catch(exception $e)
        {
            $this->on_permission_requirement_failed();
            return false;
        }
    }

    protected function require_permissions($ppermission)
    {
        try
        {
            $user = $this->get_user();
            if(is_array($ppermission))
            {
                foreach($ppermission as $permission)
                {
                    if($user->can($permission)) continue;
                    throw new exception("Required permission missing for {$user}");
                }
                return true;
            } else {
                if($user->can($ppermission)) return true;
                throw new exception("Permission Required missing for {$user}");
            }
        }
        catch(exception $e)
        {
            $this->on_permission_requirement_failed();
            return false;
        }
    }

    protected function require_group($pgroup)
    {
        if(is_array($pgroup)){
            foreach($pgroup as $group){
                if(model::is_user_group($group)){
                    if($this->get_user()->get_group()->get_name() === $group->get_name()) return true;
                } elseif (is_string($group)){
                    if($this->get_user()->get_group()->get_name() === $group) return true;
                } elseif (is_numeric($group) || is_integer($group)){
                    if($this->get_user()->get_group()->get_id() === $group) return true;
                }
            }
        } elseif(model::is_user_group($pgroup)) {
            if($this->get_user()->get_group()->get_name() === $pgroup->get_name()) return true;
        } elseif(is_string($pgroup)){
            if($this->get_user()->get_group()->get_name() === $pgroup) return true;
        } elseif(is_numeric($pgroup) || is_integer($pgroup)){
            if($this->get_user()->get_group()->get_id() === $pgroup) return true;
        }
        $this->on_group_requirement_failed();
        return false;
    }

    public function redirect($ppath = "")
    {
        try 
        {
            if($ppath != "")
            {
                if(isset($_REQUEST["key"]) && isset($_REQUEST["output"]) && isset($_REQUEST["depth"]) && $this->is_login()) header("Location: /api/{$ppath}?key=".urlencode($_REQUEST["key"])."&output=".urlencode($_REQUEST["output"])."&depth");
                elseif(isset($_REQUEST["key"]) && isset($_REQUEST["output"]) && $this->is_login()) header("Location: /api/{$ppath}?key=".urlencode($_REQUEST["key"])."&output=".urlencode($_REQUEST["output"]));
                elseif(isset($_REQUEST["key"]) && $this->is_login()) header("Location: /api/{$ppath}?key=".urlencode($_REQUEST["key"]));
                else header("Location: /api/{$ppath}");
            }
            die();
        }
        catch (exception $e)
        {
            die();
        }
    }

}
