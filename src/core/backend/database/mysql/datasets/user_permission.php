<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;
use core\backend\database\mysql\model;

class user_permission extends dataset
{

    protected $user = NULL;

    protected $permission = NULL;

    protected $granted = NULL;

    public  function save($pid = 0)
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `user_permissions` SET user=? , permission=? , granted=? WHERE id=?","iiii",array($this->user,$this->permission,$this->granted,$this->id));
        } else {
            if($this->insert_request("INSERT INTO `user_permissions` (`user`,`permission`,`granted`) VALUES (?,?,?)","iii",array($this->user,$this->permission,$this->granted)))
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

    public  function get_permission()
    {
        return model::get_permission_by_id($this->permission);
    }

    public  function set_permission($ppermission)
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

    public  function set_granted($pbool)
    {
        if($pbool === 1 || $pbool === true)
        {
            $this->granted = 1;
            return true;
        }
        if($pbool === 0 || $pbool === false)
        {
            $this->granted = 0;
            return true;
        }
        $this->granted = 0;
        return false;
    }

    public function get_granted()
    {
        return $this->granted;
    }

    public  function is_granted()
    {
        return ($this->granted >= 1 || $this->granted === true);
    }

}
