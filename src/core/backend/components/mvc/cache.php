<?php
namespace core\backend\components\mvc;
use core\component;
use core\backend\components\mvc\user;
use core\backend\components\filesystem\file;
use core\backend\components\filesystem\folder;

/**
 * Cache
 * 
 * Control the application cache
 * 
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class cache extends component
{

    protected $reference;

    protected $required;

    public function __construct($preference)
    {
        $this->reference = $preference;
        $this->required = false;
    }

    public function save_view()
    {
        $cache_folder = "./";
        if($this->reference === "core" || $this->reference === "app")
        {
            $cache_folder .= "cache/views/".$this->get_controller_name()."/".$this->get_view_name()."/".base64_encode(urlencode($_SERVER["REQUEST_URI"]))."/";
            $cache_folder = new folder($cache_folder);
            $cache_file = new file($cache_folder.time().".html");
            $cache_file->set_contents($this->pull());
            echo $cache_file->get_contents();
        } else {
            $cache_folder .= "plugins/".$this->reference."/cache/views/".$this->get_controller_name()."/".$this->get_view_name()."/".base64_encode(urlencode($_SERVER["REQUEST_URI"]))."/";
            $cache_folder = new folder($cache_folder);
            $cache_file = new file($cache_folder.time().".html");
            $cache_file->set_contents($this->pull());
            echo $cache_file->get_contents();
        }
    }

    public function is_saved()
    {
        $cache_folder = "./";
        if($this->reference === "core" || $this->reference === "app")
        {
            $cache_folder .= "cache/views/".$this->get_controller_name()."/".$this->get_view_name()."/".base64_encode(urlencode($_SERVER["REQUEST_URI"]))."/";
            $cache_folder = new folder($cache_folder);
            return (count($cache_folder->get_files_by_extension("html")) >= 1);
        } else {
            $cache_folder .= "plugins/".$this->reference."/cache/views/".$this->get_controller_name()."/".$this->get_view_name()."/".base64_encode(urlencode($_SERVER["REQUEST_URI"]))."/";
            $cache_folder = new folder($cache_folder);
            return (count($cache_folder->get_files_by_extension("html")) >= 1);
        }
    }

    public function is_required()
    {
        return $this->required;
    }

    public function activate()
    {
        $this->required = true;
    }

    public function restore_saved_view()
    {
        $cache_folder = "./";
        if($this->reference === "core" || $this->reference === "app")
        {
            $cache_folder .= "cache/views/".$this->get_controller_name()."/".$this->get_view_name()."/".base64_encode(urlencode($_SERVER["REQUEST_URI"]))."/";
            $cache_folder = new folder($cache_folder);
            foreach($cache_folder->get_files_by_extension("html") as $cache_file)
            {
                echo $cache_file->get_contents();
                return true;
            }
        } else {
            $cache_folder .= "plugins/".$this->reference."/cache/views/".$this->get_controller_name()."/".$this->get_view_name()."/".base64_encode(urlencode($_SERVER["REQUEST_URI"]))."/";
            $cache_folder = new folder($cache_folder);
            foreach($cache_folder->get_files_by_extension("html") as $cache_file)
            {
                echo $cache_file->get_contents();
                return true;
            }
        }
        return false;
    }

}