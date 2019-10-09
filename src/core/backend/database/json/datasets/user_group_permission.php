<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;
use core\backend\database\model;

class user_group_permission extends dataset
{

    protected $user_group = NULL;

    protected $permission = NULL;

    protected $granted = NULL;

    public function __construct($pdata)
    {
        $this->table_name = "user_group_permissions";
        $this->parse_data($pdata);
    }

    public function get_user_group()
    {
        return model::get_user_group_by_id($this->user_group);
    }

    public function set_user_group($puser_group)
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

    public function set_granted($pbool)
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

    public function get_granted()
    {
        return $this->granted;
    }

    public function is_granted()
    {
        if($this->granted >= 1) return true;
        return false;
    }

}
