<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class option extends dataset
{

    protected $name = "";

    public function __construct($pdata)
    {
        $this->table_name = "options";
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

