<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;
use core\backend\database\mysql\model;

class user_group_controller_view extends dataset
{

    protected $user_group = NULL;

    protected $controller_view = NULL;

    protected $granted = 0;

    public  function save($pid = 0)
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `user_group_controller_views` SET user_group=? , controller_view=? , granted=? WHERE id=?","iiii",array($this->user_group,$this->controller_view,$this->granted,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES (?,?,?)","iii",array($this->user_group,$this->controller_view,$this->granted)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }

    public  function get_user_group()
    {
        return model::get_user_group_by_id($this->user_group);
    }

    public  function set_user_group($puser_group)
    {
        if(is_object($puser_group) && model::is_user_group($puser_group))
        {
            $this->user_group = $puser_group->get_id();
            return true;
        }
        if($puser_group != NULL && is_integer($puser_group))
        {
            $new_user_group = model::get_user_group_by_id($puser_group);
            if($new_user_group != NULL && model::is_user_group($new_user_group))
            {
                $this->user_group = $new_user_group->get_id();
                return true;
            }
        }
        return false;
    }

    public  function get_controller_view()
    {
        return model::get_controller_view_by_id($this->controller_view);
    }

    public  function set_controller_view($pcontroller_view)
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

    public  function set_granted($pbool)
    {
        if($pbool >= 1 || $pbool === true)
        {
            $this->granted = 1;
        }
        if($pbool === 0 || $pbool === false)
        {
            $this->granted = 0;
        }
    }

    public  function get_granted()
    {
        return ($this->granted >= 1 || $this->granted === true);
    }

}

