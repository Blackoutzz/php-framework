<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;

class menu_category_option extends dataset
{

    protected $option = NULL;

    protected $value = "";

    public function __construct($pdata)
    {
        $this->table_name = "menu_category_options";
        $this->parse_data($pdata);
    }

    public function __construct($pdata)
    {
        $this->table_name = "menu_category_options";
        $this->parse_data($pdata);
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
