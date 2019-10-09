<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;
use core\backend\database\model;

class user_option extends dataset
{

    protected $user = NULL;

    protected $option = NULL;

    protected $value = "";

    public  function __construct($pdata)
    {
        $this->table_name = "user_options";
        $this->parse_data($pdata);
    }

    public function __toString()
    {
        return (string) $this->value;
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

    public  function get_user()
    {
        return model::get_users_by_id($this->user);
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
            $new_user = model::get_users_by_id($puser)[0];
            if($new_user != NULL && model::is_user($new_user))
            {
                $this->user = $new_user->get_id();
                return true;
            }
        }
        return false;
    }

}
