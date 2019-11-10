<?php
namespace core\backend\components;
use core\common\str;
use core\program;

/**
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class log
{

    protected $folderpath;

    protected $name;

    public function __construct($pname="core")
    {
        $this->folderpath = program::$path."logs/";
        $this->name = $pname;
    }

    public function save($pdata)
    {
        $id = 0;
        if(isset(program::$user)) $id = program::$user->get_id();
        $folder = $this->folderpath.$this->name.DS.date("Y").DS.date("F").DS;
        $log = $this->folderpath.$this->name.DS.date("Y").DS.date("F").DS.date("j").".log";
        if(!is_dir($folder)){
            if(!mkdir($folder,755,true)){
                error_log("log::get -> Permission denied to create log's folder.");
                return false;
            }
        }
        if(!is_file($log))
        {
            if(file_put_contents($log,json_encode(array())) === false){
                error_log("log::get -> Permission denied to create log's file.");
                die("Permission denied to create log's file.");
            }
        }
        $log_data = json_decode(file_get_contents($log),true);
        if(!is_array($log_data))
        {
            $log_data = array();
            $log_data["".runid.str::get_hex($id).""] = array();
            $log_data[runid.str::get_hex($id)][] = $pdata;
        }
        if(file_put_contents($log,json_encode($log_data)) === false)
        {
            error_log("log::save -> Permission denied to create log's file.");
            die("Permission denied to write to log's file.");
        }
        return true;
    }

    public function get_daily_logs()
    {
        $folder = $this->folderpath.$this->name.DS.date("Y").DS.date("F").DS;
        $log = $this->folderpath.$this->name.DS.date("Y").DS.date("F").DS.date("j").".log";
        if(!is_dir($folder)){
            if(!mkdir($folder,755,true)){
                error_log("log::get -> Permission denied to create log's folder.");
                return false;
            }
        }
        if(!is_file($log))
        {
            if(file_put_contents($log,json_encode(array())) === false){
                error_log("log::get -> Permission denied to create log's file.");
                die("Permission denied to create log's file.");
            }
        }
        return array_reverse(json_decode(file_get_contents($log),true));
    }

}