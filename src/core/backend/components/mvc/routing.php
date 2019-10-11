<?php
namespace core\backend\components\mvc;

use core\program;
use core\backend\components\database;
use core\backend\routing\request;
use core\common\str;
use core\common\time\date;
use core\common\enum;
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
        $this->parse_request();
    }

    protected function get_database()
    {
        return program::$database;
    }

    protected function has_database()
    {
        if(program::$database instanceof database);
    }

    protected function parse_request()
    {
        try
        {
            $parameter_id = 0;
            $parameters = $this->request->get_parameters();
            if(count($parameters))
            {
                if(isset($parameters[$parameter_id]) && $parameters[$parameter_id] != "")
                {
                    if($this->has_database())
                    {
                        $database = $this->get_database();
                        if($database->has_connection())
                        {
                            if($database->get_connection()->is_connected())
                            {
                                $database = $this->get_database();
                                if($this->controller = $database->get_model()->get_controller_by_name($parameters[$parameter_id]))
                                {
                                    $parameter_id++;
                                    if($this->view = $database->get_view_by_name($parameters[$parameter_id]))
                                    {
                                        $parameter_id++;
                                        if($this->controller_view = $database->get_model()->get_controller_view_by_controller_and_view($this->controller,$this->view))
                                        {
                                            $view_parameters = array();
                                            while(isset($this->parameters[$parameter_id]))
                                            {
                                                $view_parameters[] = $this->parameters[$parameter_id];
                                                $parameter_id++;
                                            }
                                            $this->parameters = $view_parameters;
                                            return true;
                                        } else {
                                           throw new exception("Invalid controller view",404);
                                        }
                                    } else {
                                        throw new exception("Invalid view",404);
                                    }
                                } else {
                                    throw new exception("Invalid controller",404);
                                }
                            } else {
                                throw new exception("Database not available",503);
                            }
                        } else {
                            //Not setup
                            throw new exception("Database not installed",503);
                        }
                    } else {
                        //Static route
                        $namespace = $this->parse_controller_namespace($parameters[$parameter_id]);
                        if(class_exists($namespace))
                        {
                            $this->controller = new controller(array("id"=>0,"name"=>$this->parse_controller_name($parameters[$parameter_id])));
                            $parameter_id++;
                            $controller = new $namespace();
                            if($controller->has_view($parameters[$parameter_id]))
                            {
                                $this->view = new view(array("id"=>0,"name"=>$this->parse_view_name($parameters[$parameter_id])));
                                $parameter_id++;
                                $this->controller_view = new controller_view(array("id"=>0,"controller"=>0,"view"=>0));
                                $view_parameters = array();
                                while(isset($this->parameters[$parameter_id]))
                                {
                                    $view_parameters[] = $this->parameters[$parameter_id];
                                    $parameter_id++;
                                }
                                $this->parameters = $view_parameters;
                                return true;
                            } else {
                                if(program::$user->is_connected() && $controller->has_view("dashboard"))
                                {       
                                    $this->view = new view(array("id"=>2,"name"=>"dashboard"));
                                    $this->controller_view = new controller_view(array("id"=>2,"controller"=>1,"view"=>2));
                                    $view_parameters = array();
                                    while(isset($this->parameters[$parameter_id]))
                                    {
                                        $view_parameters[] = $this->parameters[$parameter_id];
                                        $parameter_id++;
                                    }
                                    $this->parameters = $view_parameters;
                                    return true;
                                } 
                                elseif($controller->has_view("index"))
                                {
                                    $this->view = new view(array("id"=>1,"name"=>"index"));
                                    $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                                    $view_parameters = array();
                                    while(isset($this->parameters[$parameter_id]))
                                    {
                                        $view_parameters[] = $this->parameters[$parameter_id];
                                        $parameter_id++;
                                    }
                                    $this->parameters = $view_parameters;
                                    return true;
                                }
                            }
                            throw new exception("Invalid view",404);
                        } else {
                            throw new exception("Invalid controller",404);
                        }
                    }
                } else {
                    $this->controller = new controller(array("id"=>1,"name"=>"root"));
                    if(program::$user instanceof user)
                    {
                        if(program::$user->is_connected())
                        {
                            $this->view = new view(array("id"=>2,"name"=>"dashboard"));
                            $this->controller_view = new controller_view(array("id"=>2,"controller"=>1,"view"=>2));
                        } else {
                            $this->view = new view(array("id"=>1,"name"=>"index"));
                            $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                        }
                    } else {
                        $this->view = new view(array("id"=>1,"name"=>"index"));
                        $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                    }
                    return true;
                }
            } else {
                $this->controller = new controller(array("id"=>1,"name"=>"root"));
                if(program::$user instanceof user)
                {
                    if(program::$user->is_connected())
                    {
                        $this->view = new view(array("id"=>2,"name"=>"dashboard"));
                        $this->controller_view = new controller_view(array("id"=>2,"controller"=>1,"view"=>2));
                    } else {
                        $this->view = new view(array("id"=>1,"name"=>"index"));
                        $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                    }
                } else {
                    $this->view = new view(array("id"=>1,"name"=>"index"));
                    $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
                }
                return true;
            }
        } 
        catch (exception $e)
        {
            $this->controller =  new controller(array("id"=>1,"name"=>"root"));
            $this->view = new view(array("id"=>0,"name"=>"error"));
            $this->controller_view = new controller_view(array("id"=>0,"controller"=>1,"view"=>0));
            $this->parameters = array($e->get_code(),$e->get_message());
            return false;
        }
    }

    protected function parse_controller_name($pcontroller)
    {
        try
        {
            if(preg_match('~^([A-z]+[A-z-_]*[A-z]+)$~im',trim(strtolower($pcontroller)),$controller))
            {
                $pcontroller = $controller[1];
                return $pcontroller;
            }
            throw new exception("Invalid controller name");
        } 
        catch (exception $e)
        {
            $pcontroller = "root";
            return $pcontroller;
        }
    }

    protected function parse_controller_namespace($pcontroller)
    {
        $type = $this->request->get_type();
        $controller = preg_replace('~(-)~','_',$this->parse_controller_name($pcontroller));
        $pcontroller = "controllers\\{$type}\\{$controller}";
        return $pcontroller;
    }

    protected function parse_view_name($pview)
    {
        if($view = trim(strtolower($pview)))
        {
            return $view;
        } else {
            if(program::$user instanceof user)
            {
                if(program::$user->is_connected())
                    return "dashboard";
            }
            return "index";
        }
    }

    public function get_controller_instance()
    {
        $name = $this->controller->get_name();
        $controller = $this->parse_controller_namespace($name);
        return new $controller();
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
        return $this->type;
    }

}
