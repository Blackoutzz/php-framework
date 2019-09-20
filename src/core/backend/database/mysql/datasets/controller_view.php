<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;
use core\backend\database\mysql\model;

class controller_view extends dataset
{

    protected $controller = NULL;

    protected $view = NULL;

    public  function __construct($pdata)
    {
        $this->table_name = "controller_views";
        $this->parse_data($pdata);
    }

    public  function save()
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `controller_views` SET controller=? , view=? WHERE id=?","iii",array($this->controller,$this->view,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `controller_views` (`controller`,`view`) VALUES (?,?)","ii",array($this->controller,$this->view)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }

    public  function get_controller()
    {
        return model::get_controller_by_id($this->controller);
    }

    public  function set_controller($pcontroller)
    {
        if(model::is_controller($pcontroller)){
            $this->controller = $pcontroller->get_id();
            return true;
        }
        if($pcontroller != NULL && is_integer($pcontroller)){
            $new_controller = model::get_controller_by_id($pcontroller);
            if($new_controller != NULL && model::is_controller($new_controller)){
                $this->controller = $new_controller->get_id();
                return true;
            }
        }
        return false;
    }

    public  function get_view()
    {
        return model::get_view_by_id($this->view);
    }

    public  function set_view($pview)
    {
        if(model::is_view($pview)){
            $this->view = $pview->get_id();
            return true;
        }
        if($pview != NULL && is_integer($pview)){
            $new_view = model::get_controller_by_id($this->controller);
            if($new_view != NULL && model::is_view($new_view)){
                $this->view = $new_view->get_id();
                return true;
            }
        }
        return false;
    }

    public  function __toString()
    {
        $controller = $this->get_controller();
        $view = $this->get_view();
        if(model::is_controller($controller) && model::is_view($view))
        {
            if($controller != "root") return "{$controller}/{$view}";
            else return "{$view}";
        } else {
            return "install/index";
        }

    }

}

