<?php
namespace core\backend\components\mvc;

use core\program;
use core\backend\components\database;
use core\backend\routing\request;
use core\common\str;
use core\common\time\date;
use core\backend\components\filesystem\folder;
use core\backend\components\filesystem\file;
use core\backend\database\mysql\datasets\controller;
use core\backend\database\mysql\datasets\view;
use core\backend\database\mysql\datasets\controller_view;
use core\common\exception;

/**
 * Routing for MVC
 * 
 * @version 1.0
 * @author  mick@blackoutzz.me
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class routing
{

    protected $request;

    protected $controller;

    protected $view;

    protected $controller_view;

    protected $parameters;

    public function __construct()
    {
        $this->routes = array();
        $this->request = new request();
        $this->parameters = array();
        $folder = new folder("controllers");
        $folder->import(true);
        $this->view = new view(array("name"=>"index"));
        $this->controller = new controller(array("name"=>"root"));
        $this->parse_request();
    }

    protected function get_database()
    {
        return program::$databases["mysql"];
    }

    protected function has_database()
    {
        if(program::$databases["mysql"] instanceof database);
    }

    protected function parse_request()
    {
        if($this->has_database() && $this->get_database()->has_connection())
        {
            return $this->parse_managed_request();
        } else {
            return $this->parse_unmanaged_request();
        }
    }

    protected function parse_request_parameters($pstarting_point = 0)
    {
        $parameter_id = intval($pstarting_point);
        $view_parameters = array();
        $request_parameters = $this->request->get_parameters();
        while(isset($request_parameters[$parameter_id]))
        {
            $view_parameters[] = $request_parameters[$parameter_id];
            $parameter_id++;
        }
        return $this->parameters = $view_parameters;
    }

    protected function parse_managed_request()
    {
        try
        {
            $database = $this->get_database();
            if($database->get_connection()->is_connected())
            {
                $parameters = $this->request->get_parameters();
                if(count($parameters))
                {
                    $parameter_id = 0;
                    if(isset($parameters[$parameter_id]) && $parameters[$parameter_id] != "")
                    {
                        if($this->parse_managed_controller($parameters[$parameter_id])) $parameter_id++;
                        elseif(!$this->on_default_controller()) return false;
                        if(isset($parameters[$parameter_id]) && $parameters[$parameter_id] != "")
                        {
                            if($this->parse_managed_view($parameters[$parameter_id])) $parameter_id++;
                            elseif(!$this->on_default_view()) return false;
                            $this->parse_request_parameters($parameter_id);
                            return true;
                        }
                        if(!$this->on_default_view()) return false;
                        $this->parse_request_parameters($parameter_id);
                        return true;
                    }
                } 
                if(!$this->on_default_controller_view()) return false;
                return true;
            } else {
                throw new exception("Database unavailable.",503);
            }
        } 
        catch (exception $e)
        {
            return $this->on_request_error($e);
        }
    }

    protected function parse_unmanaged_request()
    {
        try
        {
            $parameters = $this->request->get_parameters();
            if(count($parameters))
            {
                $parameter_id = 0;
                if(isset($parameters[$parameter_id]) && $parameters[$parameter_id] != "")
                {
                    if($this->parse_unmanaged_controller($parameters[$parameter_id])) $parameter_id++;
                    elseif(!$this->on_default_controller()) return false;
                    if(isset($parameters[$parameter_id]) && $parameters[$parameter_id] != "")
                    {
                        if($this->parse_unmanaged_view($parameters[$parameter_id])) $parameter_id++;
                        elseif(!$this->on_default_view()) return false;
                        $this->parse_request_parameters($parameter_id);
                        return true;
                    }
                    if(!$this->on_default_view()) return false;
                    $this->parse_request_parameters($parameter_id);
                    return true;
                }
            }
            if(!$this->on_default_controller_view()) return false; 
            return true;
        } 
        catch (exception $e)
        {
            return $this->on_request_error($e);
        }
    }

    protected function on_default_controller()
    {
        try
        {
            $type = $this->request->get_type();
            if(class_exists("controllers\\{$type}\\root"))
            {
                $this->controller = new controller(array("id"=>1,"name"=>"root"));
                return true;
            }
            throw new exception("Default {$type} controller not found",503);
        }
        catch (exception $e)
        {
            $this->on_controller_not_found();
            return $this->on_request_error($e);
        }
    }

    protected function on_default_view()
    {
        try
        {
            $type = $this->request->get_type();
            $namespace = "controllers\\{$type}\\root";
            if(class_exists($namespace))
            {
                if(program::$users[0] instanceof user)
                {
                    if(program::$users[0]->is_connected())
                    {
                        if(method_exists($namespace,"dashboard"))
                        {
                            $this->view = new view(array("id"=>2,"name"=>"dashboard"));
                            $this->controller_view = new controller_view(array("id"=>2,"controller"=>1,"view"=>2));
                            return true;
                        } else {
                            if(method_exists($namespace,"index"))
                            {
                                $this->view = new view(array("id"=>1,"name"=>"index"));
                                $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                                return true;
                            }
                            throw new exception("Default {$type} view isn't created",404);
                        }
                    } else {
                        if(method_exists($namespace,"index"))
                        {
                            $this->view = new view(array("id"=>1,"name"=>"index"));
                            $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                            return true;
                        }
                        throw new exception("Default {$type} view isn't created",404);
                    }
                } else {
                    if(method_exists($namespace,"index"))
                    {
                        $this->view = new view(array("id"=>1,"name"=>"index"));
                        $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                        return true;
                    }
                    throw new exception("Default {$type} view isn't created",404);
                }
            } 
            throw new exception("Default {$type} controller isn't created",503);
        }
        catch (exception $e)
        {
            return $this->on_request_error($e);
        }
        
    }

    protected function on_default_controller_view()
    {
        if(!$this->on_default_controller()) return false;
        if(!$this->on_default_view()) return false;
        return true;
    }

    protected function on_controller_not_found()
    {
        $type = $this->request->get_type();
        $controller_data ="<?php
        namespace controllers\{type};
        use core\backend\components\mvc\controllers\{type};
                
        class root extends {type}
        {

        }
        ";
        $controller_data = str_replace('{type}',$type,$controller_data);
        $controller_data = str_replace("    ","",$controller_data);
        $file = new file(program::$path."controllers/{$type}/root.php");
        if($file->set_contents($controller_data))
        {
            return $file->import();
        }
        return false;
    }

    protected function on_request_error(exception $pexception)
    {
        $this->controller =  new controller(array("id"=>1,"name"=>"root"));
        $this->view = new view(array("id"=>0,"name"=>"error"));
        $this->controller_view = new controller_view(array("id"=>0,"controller"=>1,"view"=>0));
        $this->parameters = array($pexception->get_code(),$pexception->get_message());
        return false;
    }

    protected function parse_managed_controller($pcontroller)
    {
        try
        {
            if($database = $this->get_database())
            {
                if($controller_name = $this->parse_controller_name($pcontroller))
                {
                    if($this->controller = $database->get_model()->get_controller_by_name($controller_name))
                    {
                        if($controller_namespace = $this->parse_controller_namespace($controller_name))
                        {
                            if(class_exists($controller_namespace)) return true;
                        }
                        throw new exception("Controller isn't setup",503);
                    } else {
                        throw new exception("Controller doesn't exist",0);
                    }
                } else {
                    throw new exception("Impossible controller name",0);
                }
            } else {
                throw new exception("Impossible to manage the request , database is missing",503);
            }
        }
        catch (exception $e)
        {
            if($e->get_code() != 0)
            {
                return $this->on_request_error($e);
            } else {
                return $this->on_default_controller();
            }
        }
    }

    protected function parse_managed_view($pview)
    {
        try
        {
            if($database = $this->get_database())
            {
                if($view_name = $this->parse_view_name($pview))
                {
                    if($this->view = $database->get_view_by_name($pview))
                    {
                        if($this->controller_view = $database->get_model()->get_controller_view_by_controller_and_view($this->controller,$this->view))
                        {
                            if(method_exists($this->parse_controller_namespace($this->controller->get_name()),$view_name))
                            {
                                return true;
                            } else {
                                throw new exception("View isn't configured",404);
                            }
                        } else {
                            throw new exception("Invalid controller view",404);
                        }
                    } else {
                        throw new exception("Invalid view",404);
                    }
                } else {
                    throw new exception("Impossible view name",0);
                }
            } else {
                throw new exception("Impossible to manage the request , database is missing",503);
            }
        } 
        catch (exception $e)
        {
            if($e->get_code() != 0)
            {
                return $this->on_request_error($e);
            } else {
                return $this->on_default_view();
            }
        }
    }

    protected function parse_unmanaged_view($pview)
    {
        try
        {
            if($view_name = $this->parse_view_name($pview))
            {
                if((($this->get_controller_type() === "api" || $this->get_controller_type() === "ajax") && method_exists($this->parse_controller_namespace($this->controller->get_name()),$this->get_view_prefix().$view_name))
                || method_exists($this->parse_controller_namespace($this->controller->get_name()),$view_name))
                {
                    $this->view = new view(array("name"=>$view_name));
                    $this->controller_view = new controller_view(array("controller"=>$this->controller,"view"=>$this->view));
                    return true;
                } else {
                    throw new exception("View isn't configured",404);
                }
            } else {
                throw new exception("Impossible view name",0);
            }
        } 
        catch (exception $e)
        {
            $this->on_default_view();
            return false;
        }
    }

    protected function parse_unmanaged_controller($pcontroller)
    {
        try
        {
            if($controller_name = $this->parse_controller_name($pcontroller))
            {
                if($controller_namespace = $this->parse_controller_namespace($controller_name))
                {
                    if(class_exists($controller_namespace))
                    {
                        $this->controller = new controller(array("name"=>$controller_name));
                        return true;
                    } 
                    throw new exception("Controller isn't setup",503);
                }
                throw new exception("Impossible controller ",503);
            } else {
                throw new exception("Controller doesn't exist",0);
            }
        }
        catch (exception $e)
        {
            $this->on_default_controller();
            return false;
        }
    }

    protected function parse_controller_name($pcontroller)
    {
        try
        {
            if(preg_match('~^([A-z]+[A-z-_]*[A-z]+)$~im',trim(strtolower($pcontroller)),$controller))
            {
                return $controller[1];
            }
            throw new exception("Invalid controller name");
        } 
        catch (exception $e)
        {
            return false;
        }
    }

    protected function parse_controller_namespace($pcontroller)
    {
        try
        {
            $type = $this->request->get_type();
            if($controller_name = $this->parse_controller_name($pcontroller))
            {
                $controller = preg_replace('~(-)~','_',$controller_name);
                return "controllers\\{$type}\\{$controller}";
            } else {
                throw new exception("Invalid controller name for namespace");
            }
        } 
        catch (exception $e)
        {
            return false;
        }
    }

    protected function get_view_prefix()
    {
        switch(strtolower($_SERVER["REQUEST_METHOD"]))
        {
            case "get":
                return "get_";
            case "put": 
                return "update_";
            case "delete":
                return "delete_";
            case "post":
                return "add_";
            default:
                return "get_";
        }
    }

    protected function parse_view_name($pview)
    {
        try
        {
            $view = trim(strtolower($pview));
            if(preg_match('~^([A-z]+[A-z-_]*[A-z]+)$~im',$view,$view_names))
            {
                return $view_names[1];
            } else {
               throw new exception("Invalid view name");
            }
        } 
        catch(exception $e)
        {
            return $this->on_default_view();
        }
    }

    public function get_controller_instance()
    {
        try 
        {
            $name = $this->controller->get_name();
            $controller = $this->parse_controller_namespace($name);
            if(class_exists($controller)) return new $controller();
            else throw new exception("Invalid Controller Namespace",404);
        } 
        catch(exception $e)
        {
            $this->on_request_error($e);
            $name = $this->controller->get_name();
            $controller = $this->parse_controller_namespace($name);
            if(class_exists($controller)) return new $controller();
            else return false;
        }
    }

    public function get_url()
    {
        return str::sanitize($_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
    }

    public function get_port()
    {
        return $_SERVER["SERVER_PORT"];
    }

    public function get_date()
    {
        return new date($_SERVER["REQUEST_TIME"]);
    }

    public function get_parameters()
    {
        return $this->parameters;
    }

    public function get_controller()
    {
        return $this->controller;
    }

    public function get_controller_view()
    {
        return $this->controller_view;
    }

    public function get_view()
    {
        return $this->view;
    }

    public function get_controller_type()
    {
        return $this->request->get_type();
    }

}
