<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;
use core\backend\database\model;

class user_controller_view extends dataset
{

    protected $user = NULL;

    protected $controller_view = NULL;

    protected $granted = 0;

    public  function __construct($pdata)
    {
        $this->table_name = "user_controller_views";
        $this->parse_data($pdata);
    }

    public  function save()
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `user_controller_views` SET user=? , controller_view=? , granted=? WHERE id=?","iiii",array($this->user,$this->controller_view,$this->granted,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `user_controller_views` (`user`,`controller_view`,`granted`) VALUES (?,?,?)","iii",array($this->user,$this->controller_view,$this->granted)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }

    public  function get_user()
    {
        return model::get_user_by_id($this->user);
    }

    public  function set_user($puser)
    {
        if(model::is_user($puser))
        {
            $this->user = $puser->get_id();
            return true;
        }
        if($puser != NULL && is_integer($puser))
        {
            $new_user = model::get_user_by_id($puser);
            if($new_user != NULL && model::is_user($new_user))
            {
                $this->user = $new_user->get_id();
                return true;
            }
        }
        return false;
    }

    public  function get_controller()
    {
        return model::get_controller_by_id($this->controller);
    }

    public  function set_controller($pcontroller)
    {
        if(model::is_controller($pcontroller))
        {
            $this->controller = $pcontroller->get_id();
            return true;
        }
        if($pcontroller != NULL && is_integer($pcontroller))
        {
            $new_controller = model::get_controller_by_id($pcontroller);
            if($new_controller != NULL && model::is_controller($new_controller))
            {
                $this->controller = $new_controller->get_id();
                return true;
            }
        }
        return false;
    }

    public  function set_granted($pbool)
    {
        if($pbool >= 1 || $pbool === true)
        {
            $this->granted = 1;
            return true;
        }
        if($pbool <= 0 || $pbool === false)
        {
            $this->granted = 0;
            return true;
        }
        $this->granted = 0;
        return false;
    }

    public  function get_granted()
    {
        return ($this->granted >= 1 || $this->granted === true);
    }

}
