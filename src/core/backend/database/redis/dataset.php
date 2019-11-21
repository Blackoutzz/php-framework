<?php
namespace core\backend\database\mysql;
use core\backend\database\dataset as database_dataset;

/**
 * Redis Dataset 
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class dataset extends database_dataset
{

    protected $key;

    protected $value;

    public function save($pid=0)
    {
        $id = intval($pid);
        if($db = $this->get_databases()->get_redis_database_by_id($id))
        {
            if($db->get_connection()->set_key($this->key,$this->value)) return true; 
        }
        return false; 
    }

    public function delete($pid=0)
    {
        $id = intval($pid);
        if($db = $this->get_databases()->get_redis_database_by_id($id))
        {
            if($db->get_connection()->delete($this->key,$this->value)) return true; 
        }
        return false; 
    }

    public function exist($pid=0)
    {
        $id = intval($pid);
        if($this->key !== NULL && $this->key !== false && $this->key != "")
        {
            if($db = $this->get_databases()->get_redis_database_by_id($id))
            {
                if($db->get_connection()->key_exist()) return true; 
            }
        }
        return false;
    }

}
