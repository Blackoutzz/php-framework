<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class view extends dataset
{

    protected $name = "";

    public function __construct($pdata)
    {
        $this->table_name = "views";
        $this->parse_data($pdata);
    }

    public  function save()
    {
        if($this->exist())
        {
            return $this->update_request("UPDATE `views` SET name=? WHERE id=?","si",array($this->name,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `views` (`name`) VALUES (?)","s",array($this->name)))
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
