<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class action extends dataset
{

    protected $name = "";

    public  function save($pid = 0)
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `actions` SET name=? WHERE id=?","i",array($this->name,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `actions` (`name`) VALUES (?)","s",array($this->name))){
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
