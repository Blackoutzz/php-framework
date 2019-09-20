<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class app_options extends dataset
{

    protected $option;

    protected $value;

    public  function __construct($pdata)
    {
        $this->table_name = "app_options";
        $this->parse_data($pdata);
    }

    public  function save()
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `app_options` SET option=? , value=? WHERE id=?","isi",array($this->option,$this->value,$this->id));
        } else {
            if($this->insert_prepared_request("INSERT INTO `app_options` (`option`,`value`) VALUES (?,?)","is",array($this->option,$this->value)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }
}
