<?php
namespace core\backend\components\system\programs;
use core\backend\filesystem\file;
use core\backend\system\console;

/**
 * python short summary.
 *
 * python description.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class python extends console
{

    protected $installed = false;

    protected function on_windows()
    {
        if($this->excution_path == "") $this->execution_path = "C:\\python2.7\\";
        $this->application = "python.exe";
        if(is_file($this->execution_path.$this->application)) $this->installed = true;
    }

    protected function on_unix()
    {
        $this->application = "python";
        if(preg_match("~Python ([0-9\.\-\_A-z]+)\n~im",$this->execute(array("-V")))) $this->installed = true;
    }

    protected function execute_script($pscript,$pparams = array())
    {
        if(file::exist("{$pscript}") && file::get_extension("{$pscript}") == "py")
        {
            return parent::execute(array_merge(array($pscript),$pparams));
        }
        return "Python script not found";
    }

    public function get_version()
    {
        if($this->installed){
            $full_version = $this->execute(array("-V"));
            if(preg_match("~Python ([0-9\.\-\_A-z]+)\n~im",$full_version,$version)) return $version[1];
            return "Unknown version";
        }
        return "Python isn't installed";
    }

}

class python2 extends python
{

    public function __construct()
    {
        //Python2.7 32bit
        //Python2.7 64bit
    }

}

class python3 extends python
{

    public function __construct()
    {
        //Python3.6 32bit
    }

}