<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;
use core\backend\database\mysql\model;

class user_group extends dataset
{

    protected $name = "";

    public function save($pid = 0)
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `user_groups` SET name=? WHERE id=?","si",array($this->name,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `user_groups` (`name`) VALUES (?)","s",array($this->name)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($pname)
    {
        $this->name = $this->get_sanitized_string($pname);
    }


    public  function get_permissions()
    {
        return model::get_user_group_permissions_by_user_group($this);
    }

    public  function get_controller_views()
    {
        return model::get_user_group_controller_views_by_user_group($this);
    }

    public function get_options()
    {
        return model::get_user_group_options_by_user_group($this);
    }

}
