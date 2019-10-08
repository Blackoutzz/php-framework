<?php
namespace core\backend\components\mvc;
use core\program;
use core\backend\components\mvc\user;
use core\backend\database\mysql\model;
use core\component;
use core\common\str;
use core\common\exception;
use core\backend\components\mvc\cache;
use core\frontend\components\mvc\controller_view;

/**
 * Controller
 * 
 * Application base controller for MVC
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

abstract class controller extends component
{

    protected $reference;

    protected $view_data = array();

    protected $cache;

    public function __construct($preference = "core")
    {
        $this->reference = $preference;
        $this->cache = new cache($preference);
        $this->on_initialize();
    }

    public function __toString()
    {
        return array_pop(explode('\\',__CLASS__));
    }

    public function has_access()
    {
        try
        {
            if(isset(program::$user) && program::$user instanceof user)
            {
                if(program::$user->has_access()) return true;
                throw new exception("Access denied to access view");
            } else {
                return true;
            }

        } catch (exception $e)
        {
            $this->on_access_failed();
            return false;
        }
    }

    public function has_view($pview = false)
    {
        try
        {
            if(!$pview) 
                $view = $this->get_view_name();
            else
                $view = trim(strtolower(str_replace("-","_",$pview)));
            if(preg_match('~^([A-z]+[A-z-_]*[A-z]+)$~im',$view))
            {
                if(method_exists('core\\backend\\components\\mvc\\controller',$view)) 
                    throw new exception("Reserved view name");
                if(method_exists($this,$view))
                {
                    return true;
                } else {
                    throw new exception("No view configured inside controller");
                }
            } else {
                throw new exception("Invalid view name");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    protected function prepare_view()
    {
        try
        {
            $no_parameter = 0;
            $count_view_parameters = count($this->get_parameters());
            $count_needed_parameters = $this->count_view_parameters();
            $view_parameters = $this->get_parameters();
            //If No parameters are needed they will be ignored
            if($count_view_parameters === $no_parameter
            && $count_needed_parameters === $no_parameter)
            {
                return call_user_func(array($this,str_replace("-","_",$this->get_view_name())));
            }
            //If Parameters match perfectly
            if($count_view_parameters === $count_needed_parameters)
            {
                return call_user_func_array(array($this,str_replace("-","_",$this->get_view_name())),$view_parameters);
            }
            //View will be loaded with the first parameter provided
            if($count_view_parameters > $count_needed_parameters)
            {
                $params = array();
                for($i=0; $i > $count_needed_parameters; $i++)
                {
                    $params[] = $view_parameters[$i];

                }
                return call_user_func_array(array($this,str_replace("-","_",$this->get_view_name())),$params);

            }
            //View will be loaded with the first parameter provided and add false to the rest
            if($count_view_parameters < $count_needed_parameters)
            {
                $params = array();
                foreach($view_parameters as $param)
                {
                    $params[] = $param;
                }
                for($i=count($params);$i < $count_needed_parameters;$i++)
                {
                    $params[] = false;
                }
                return call_user_func_array(array($this,str_replace("-","_",$this->get_view_name())),$params);
            }
            //Missing or Invalid Parameters
            throw new exception("Invalid parameters.");
        } 
        catch (exception $e)
        {
            return false;
        }
    }

    protected function create_view()
    {
        return new controller_view($this->reference,$this->cache,$this->view_data);
    }

    public function initialize()
    {
        return false;
    }

    protected function require_login()
    {
        try
        {
            if($this->is_login()) return true;
            throw new exception("Login required to access ".$this->get_controller_view());
        } 
        catch(exception $e)
        {
            $this->on_login_requirement_failed();
            return false;
        }
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
                throw new exception("Required permission missing for {$user}");
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
        if(is_array($pgroup))
        {
            foreach($pgroup as $group)
            {
                if(model::is_user_group($group))
                {
                    if($this->get_user()->get_group()->get_name() === $group->get_name()) return true;
                } elseif (is_string($group))
                {
                    if($this->get_user()->get_group()->get_name() === $group) return true;
                } elseif (is_numeric($group) || is_integer($group))
                {
                    if($this->get_user()->get_group()->get_id() === $group) return true;
                }
            }
        } 
        elseif(model::is_user_group($pgroup)) 
        {
            if($this->get_user()->get_group()->get_name() === $pgroup->get_name()) return true;
        } 
        elseif(is_string($pgroup))
        {
            if($this->get_user()->get_group()->get_name() === $pgroup) return true;
        } 
        elseif(is_numeric($pgroup) || is_integer($pgroup))
        {
            if($this->get_user()->get_group()->get_id() === $pgroup) return true;
        }
        $this->on_group_requirement_failed();
        return false;
    }

    protected function require_plugin($pplugin)
    {
        try
        {
            foreach(program::get_plugins() as $plugin_slug => $plugin)
            {
                if($plugin_slug === $pplugin) return true;
                if($pplugin === $plugin) return true;
            }
            throw new exception("Plugin {$pplugin} Required and Installed.");
        } 
        catch (exception $e)
        {
            $this->on_plugin_requirement_failed();
            return false;
        }
    }

    protected function require_caching()
    {
        $this->cache->activate();
        return (!$this->cache->is_saved());
    }

    protected function send($pvar,$pname = false)
    {
        try
        {
            if($pname === false)
            {
                $this->view_data[] = $pvar;
            } else {
                $this->view_data[$pname] = $pvar;
            }
            return true;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    protected function get_name()
    {
        return program::$routing->get_controller()->get_name();
    }

    protected function get_id()
    {
        return program::$routing->get_controller()->get_id();
    }

    protected function count_view_parameters()
    {
        try
        {
            if($this->has_view())
            {
                $ref = new \ReflectionMethod($this,str_replace("-","_",$this->get_view_name()));
                return count($ref->getParameters());
            } else {
                throw new exception("Impossible to find controller so no parameters accepted.");
            }
        }
        catch (exception $e)
        {
            return 0;
        }
    }

    protected function get_parameters()
    {
        return program::$routing->get_parameters();
    }

    public function redirect($ppath = "")
    {
        try 
        {
            if($ppath != "")
            {
                header("Location: {$ppath}");
            } else {
                if(method_exists($this,"dashboard"))
                {
                    if($this->has_access() && program::$user->is_connected())
                    {
                        if($this->controller_name != "root")
                        {
                            header("Location: /{$this->controller_name}/");
                        } else {
                            header("Location: /");
                        }
                    }
                }
                if(method_exists($this,"index"))
                {
                    if($this->has_access()){
                        if($this->controller_name != "root")
                        {
                            header("Location: /{$this->controller_name}/");
                        } else {
                            header("Location: /");
                        }
                    }
                }
                header("Location: /");
            }

        } 
        catch (Exception $e)
        {
            die();
        }
        die();
    }

    protected function is_login()
    {
        return program::$user->is_connected();
    }

    //Event Related
    protected function on_initialize()
    {
        //Override this to execute custom code.
    }

    protected function on_group_requirement_failed()
    {
        //Override this to execute custom code.
        $this->on_requirement_failed();
    }

    protected function on_login_requirement_failed()
    {
        //Override this to execute custom code.
        $this->redirect("/login");
    }

    protected function on_permission_requirement_failed()
    {
        //Override this to execute custom code.
        $this->on_requirement_failed();
    }

    protected function on_plugin_requirement_failed()
    {
        //Override this to execute custom code.
        $this->on_requirement_failed();
    }

    protected function on_requirement_failed()
    {
        $this->redirect();
    }

    protected function on_access_failed()
    {
        //Override this to execute custom code.
        $this->redirect("/");
    }

}
