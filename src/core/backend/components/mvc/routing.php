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
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class routing
{

    protected $routes;

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
        $parameter_id = 0;
        $parameters = $this->request->get_parameters();
        if(isset($parameters[$parameter_id]) && $parameters[$parameter_id] != "")
        {
            if($this->get_controller_from_parameter($parameters[$parameter_id])) $parameter_id++;
            if($this->get_view_from_parameter($parameters[$parameter_id])) $parameter_id++;
            $this->set_controller_view();
            $view_parameters = array();
            while(isset($this->parameters[$parameter_id]))
            {
                $view_parameters[] = $this->parameters[$parameter_id];
                $parameter_id++;
            }
            $this->parameters = $view_parameters;
        }
    }

    protected function set_controller_from_parameter($pparameter)
    {
        $parameter = strtolower($pparameter);
        if($this->has_database())
        {
            $database = $this->get_database();
            if($this->controller = $database->get_controller_by_name($parameter))
                return true;
            $this->controller =  new controller(array("id"=>1,"name"=>"root"));
            return false;
        } else {
            $type = $this->request->get_type();
            $controller_folder = new folder('controllers/'.$type.'/',false);
            if($controller_folder->exist())
            {
                foreach($controller_folder->get_files_by_extension("php") as $file)
                {
                    if($file->get_name() === $parameter && $parameter != "install")
                    {
                        $this->controller = new controller(array("id"=>0,"name"=>$parameter));
                        return true;
                    } 
                }
            }
            $this->controller =  new controller(array("id"=>1,"name"=>"root"));
            return false;
        }
    }
    
    protected function set_view_from_parameter($pparameter)
    {
        $parameter = strtolower($pparameter);
        if($this->has_database())
        {
            $database = $this->get_database();
            if($this->view = $database->get_view_by_name($parameter)) return true;
            $this->view =  new view(array("id"=>1,"name"=>"root"));
            return false;
        } else {
            $type = $this->request->get_type();
            $controller_folder = new folder('controllers/'.$type.'/',false);
            if($controller_folder->exist())
            {
                foreach($controller_folder->get_files_by_extension("php") as $file)
                {
                    if($file->get_name() === $parameter && $parameter != "install")
                    {
                        $this->controller = new controller(array("id"=>0,"name"=>$parameter));
                        if(!class_exists("controllers\\{$type}\\{$parameter}"))
                            $file->import();
                        return true;
                    } 
                }
            }
            $this->controller =  new controller(array("id"=>1,"name"=>"root"));
            return false;
        }
    }

    protected function set_controller_view()
    {
        try
        {
            if($this->has_database())
            {
                $database = $this->get_database();
                if($this->controller_view = $database->get_controller_view_by_controller_and_view($this->controller,$this->view))
                    return true;
                throw new exception("404 - Page not found.");
            } 
            else 
            {
                throw new exception("404 - Page not found.");
            }
        } 
        catch (exception $e)
        {
            $this->on_default_controller_view();
            return false;
        }
        
    }

    public function on_default_controller_view()
    {
        $this->controller = new controller(array("id"=>1,"name"=>"root"));
        if(program::$user->is_connected()){
            $this->view = new view(array("id"=>2,"name"=>"dashboard"));
            $this->controller_view = new controller_view(array("id"=>2,"controller"=>1,"view"=>2));
        } else {
            $this->view = new view(array("id"=>1,"name"=>"index"));
            $this->controller_view = new controller_view(array("id"=>1,"controller"=>1,"view"=>1));
        }
    }

    public function on_default_view()
    {
        if(program::$user->is_connected())
        {
            $this->view = new view(array("id"=>2,"name"=>"dashboard"));
        } else {
            $this->view = new view(array("id"=>1,"name"=>"index"));
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
        return $this->type;
    }

}
