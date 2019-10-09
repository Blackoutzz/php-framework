<?php
namespace core\backend\database\json\datasets;
use core\backend\database\dataset;


class permission extends dataset
{

    protected $name = "";

    protected $description = "";

    public function __construct($pdata)
    {
        $this->table_name = "permissions";
        $this->parse_data($pdata);
    }

    public function save()
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `permissions` SET name=?,description=? WHERE id=?","ssi",array($this->name,$this->description,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `permissions` (`name`,`description`) VALUES (?,?)","ss",array($this->name,$this->description)))
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

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($pdescription)
    {
        $this->description = $this->get_sanitized_string($pdescription);
    }

}
