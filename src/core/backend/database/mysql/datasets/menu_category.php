<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class menu_category extends dataset
{

    protected $name = "";

    public function __construct($pdata)
    {
        $this->table_name = "menu_categories";
        $this->parse_data($pdata);
    }

    public function save()
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `menu_categories` SET name=? WHERE id=?","si",array($this->name,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `menu_categories` (`name`) values (?)","s",array($this->name)))
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

}
