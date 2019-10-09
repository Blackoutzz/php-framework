<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class menu_category_option extends dataset
{

    protected $menu_category = NULL;

    protected $option = NULL;

    protected $value = "";

    public function __construct($pdata)
    {
        $this->table_name = "menu_category_options";
        $this->parse_data($pdata);
    }
  
    public function save()
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `menu_category_options` SET menu_button=?,option=?,value=? WHERE id=?","iisi",array($this->menu_category,$this->option,$this->value,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `menu_category_options` (`menu_category`,`option`,`value`) values (?,?,?)","iis",array($this->menu_category,$this->option,$this->value)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }

    public function get_option()
    {
        return $this->option;
    }

    public function get_value()
    {
        return $this->value;
    }

}
