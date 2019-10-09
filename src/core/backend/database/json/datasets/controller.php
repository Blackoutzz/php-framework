<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\dataset;
use core\backend\database\model;

class controller extends dataset
{

    protected $name = "";

    public function __construct($pdata)
    {
        $this->table_name = "controllers";
        $this->parse_data($pdata);
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($pname)
    {
        $this->name = $this->get_sanitized_string($pname);
    }
    
    public function get_views()
    {
        return model::get_controller_views_by_controller($this);
    }

    public  function add_view($pview)
    {
        $view = NULL;
        if($pview instanceof view)
        {
            $view = $pview;
        }
        if($view instanceof view)
        {
            if(count(model::get_controller_view_by_controller_and_view($this,$view)) == 0)
            {
                $new_controller_view = new controller_view(array("controller"=>$this->id,"view"=>$view->get_id()));
                return $new_controller_view->save();
            }
        }
        return false;
    }

    public  function remove_view($pview)
    {
        $cv = model::get_controller_view_by_controller_and_view($this,$pview);
        if(model::is_user_controller_view($cv))
        {
            $cv->destroy();
        }
    }

}
