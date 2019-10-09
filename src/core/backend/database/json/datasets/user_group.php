<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;
use core\backend\database\model;

class user_group extends dataset
{

    protected $name = "";

    public  function __construct($pdata)
    {
        $this->table_name = "user_groups";
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
