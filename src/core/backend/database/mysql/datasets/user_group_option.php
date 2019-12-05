<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;
use core\backend\database\mysql\model;

class user_group_option extends dataset
{

    protected $user_group = NULL;

    protected $option = NULL;

    protected $value = "";

    public  function save($pid = 0)
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `user_group_options` SET user_group=? , option=? , value=? WHERE id=?","iisi",array($this->user_group,$this->option,$this->value,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `user_group_options` (`user_group`,`option`,`value`) VALUES (?,?,?)","iis",array($this->user_group,$this->option,$this->value)))
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
            $new_user_group = model::get_user_groups_by_id($puser_group)[0];
            if($new_user_group != NULL && model::is_user_group($new_user_group))
            {
                $this->user_group = $new_user_group->get_id();
                return true;
            }
        }
        return false;
    }

    public  function get_option()
    {
        return model::get_option_by_id($this->option);
    }

    public  function set_option($poption)
    {
        if(model::is_option($poption))
        {
            $this->option = $poption->get_id();
            return true;
        }
        if($poption != NULL && is_integer($poption))
        {
            $new_option = model::get_option_by_id($poption);
            if($new_option != NULL && model::is_option($new_option))
            {
                $this->option = $new_option->get_id();
                return true;
            }
        }
        return false;
    }

    public  function get_value()
    {
        return $this->value;
    }

    public  function set_value($pvalue)
    {
        $this->value = $pvalue;
    }

}
