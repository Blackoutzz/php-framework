<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class plugin_option extends dataset
{

    protected $plugin = NULL;

    protected $option = NULL;

    protected $value = "";

    public  function __construct($pdata)
    {
        $this->table_name = "plugin_options";
        $this->parse_data($pdata);
    }

    public  function save()
    {
        if($this->exist())
        {
            return $this->update_prepared_request("UPDATE `plugin_options` SET option=? , value=? WHERE id=? AND plugin=?","isii",array($this->option,$this->value,$this->id,$this->plugin));
        } else {
            if($this->insert_prepared_request("INSERT INTO `plugin_options` (`plugin`,`option`,`value`) VALUES (?,?,?)","iis",array($this->plugin,$this->option,$this->value)))
            {
                $this->id = $this->get_last_id();
                return true;
            }
            return false;
        }
    }
    
}
