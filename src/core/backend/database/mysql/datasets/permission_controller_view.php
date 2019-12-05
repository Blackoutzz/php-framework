<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;
use core\backend\database\mysql\model;

class permission_controller_view extends dataset
{

    protected $permission = NULL;

    protected $controller_view = NULL;

    protected $granted = 0;

    public function save($pid = 0)
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `permission_controller_views` SET permission=?,controller_view=?,granted=? WHERE id=?","iiii",array($this->permission,$this->controller_view,$this->granted,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) values (?,?,?)","iii",array($this->permission,$this->controller_view,$this->granted)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }

    public function get_permission()
    {
        return model::get_permission_by_id($this->permission);
    }

    public function set_permission($ppermission)
    {
        if(model::is_permission($ppermission))
        {
            $this->permission = $ppermission->get_id();
            return true;
        }
        if($ppermission != NULL && is_integer($ppermission))
        {
            $new_permission = model::get_user_group_by_id($ppermission);
            if($new_permission != NULL && model::is_permission($new_permission))
            {
                $this->permission = $new_permission->get_id();
                return true;
            }
        }
        return false;
    }

    public function set_controller_view($pcontroller_view)
    {
        if(model::is_controller_view($pcontroller_view))
        {
            $this->controller_view = $pcontroller_view->get_id();
            return true;
        }
        if($pcontroller_view != NULL && is_integer($pcontroller_view))
        {
            $new_controller_view = model::get_controller_view_by_id($pcontroller_view);
            $this->controller_view = $new_controller_view->get_id();
            if($this->controller_view != NULL) return true;
        }
        return false;
    }

    public  function get_controller_view()
    {
        return model::get_controller_view_by_id($this->controller_view);
    }

    public  function set_granted($pbool)
    {
        if($pbool >= 1 || $pbool === true) $this->granted = 1;
        if($pbool === 0 || $pbool === false) $this->granted = 0;
    }

    public  function get_granted()
    {
        return ($this->granted >= 1 || $this->granted === true);
    }

}
