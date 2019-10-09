<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;

class plugin extends dataset
{

    protected $name = "";

    protected $slug = "";

    protected $version = "ALPHA";

    protected $enabled = 0;

    public function __construct($pdata)
    {
        $this->table_name = "plugins";
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

    public function get_version()
    {
        return $this->version;
    }

    public function set_version($pversion)
    {
        $this->version = $this->get_sanitized_string($pversion);
    }

    public function get_slug()
    {
        return $this->slug;
    }

    public function set_slug($pslug)
    {
        if(sregex::is_slug($pslug))
        {
            $this->slug = $pslug;
        }
    }

    public function is_enabled()
    {
        if($this->enabled == true) return true;
        return false;
    }

    public function enable()
    {
        $this->enabled = 1;
    }

    public function disable()
    {
        $this->enabled = 0;
    }

    public function get_enabled()
    {
        return (int)$this->enabled;
    }

    public function set_enabled($penabled)
    {
        if($penabled)
        {
            $this->enabled = 1;
        } else {
            $this->enabled = 0;
        }

    }

    public function get_folder()
    {
        return new folder(".".DS."app".DS."plugins".$this->slug.DS);
    }

    public function get_instance()
    {
        $plugin_instance = "\\{$this->slug}\\core\\app";
        if(class_exists($plugin_instance))
        {
            return new $plugin_instance();
        } else {
            if(include($this->get_folder()."core".DS."main.php")) return new $plugin_instance();
        }
        return NULL;
    }

}

