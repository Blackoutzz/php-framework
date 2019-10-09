<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;

class menu_category extends dataset
{

    protected $name = "";

    public function __construct($pdata)
    {
        $this->table_name = "menu_categories";
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

}
