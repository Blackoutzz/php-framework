<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;

class app_options extends dataset
{

    protected $option;

    protected $value;

    public  function __construct($pdata)
    {
        $this->table_name = "app_options";
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
