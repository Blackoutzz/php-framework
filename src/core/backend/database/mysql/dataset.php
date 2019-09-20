<?php
namespace core\backend\database\mysql;
use core\backend\database\dataset as database_dataset;
use core\common\exception;

abstract class dataset extends database_dataset
{

    protected $table_name = NULL;

    protected $id = NULL;

    public function exist()
    {
        if($this->id !== NULL && $this->id !== false && $this->id >= 1)
        {
            return true;
        } else {
            return false;
        }
    }

    public function get_id()
    {
        return $this->id;
    }

    public function __toString()
    {
        if(isset($this->name))
        {
            return $this->name;
        }
        if(isset($this->id))
        {
            return "{$this->id}";
        }
        return "";
    }

    protected function set_id($pid = 0)
    {
        $this->id = intval($pid);
    }

    public function save()
    {

    }

    public function delete()
    {

    }

}
