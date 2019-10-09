<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;
use core\backend\database\model;

class menu_button extends dataset
{

    protected $name = "";

    protected $controller_view = NULL;

    protected $category = NULL;

    public function __construct($pdata)
    {
        $this->table_name = "menu_buttons";
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

    public function get_category()
    {
        return model::get_menu_category_by_id($this->category);
    }

    public function get_controller_view()
    {
        return model::get_controller_view_by_id($this->controller_view);
    }

}
