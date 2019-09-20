<?php
namespace core\backend\system;
use core\common\exception;

/**
 * console short summary.
 *
 * console description.
 *
 * @Version 1.0
 * @Author  mick@blackoutzz.me
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 */

abstract class console
{

    protected $program_path;

    public function __construct($pprogram_path = "")
    {
        if($this->execution_path != "" && is_string($this->program_path)) putenv("PATH=".$_ENV["PATH"].$this->program_path);
    }

    protected function on_windows()
    {
        //Overrite in your class to run this when on windows
    }

    protected function on_unix()
    {
        //Overrite in your class to run this when on unix / linux
    }

    protected function on_macos()
    {
        //Overrite in your class to run this when on mac osx
    }

}

abstract class shell extends console {}

abstract class console_program extends console
{

    protected $program;

    protected $program_path;

    //TODO : Add Macos
    public function __construct($pprogram_path = "")
    {
        if(os::is_windows())
        {
            $this->program_path = $pprogram_path;
            $this->on_windows();
        }
        elseif(os::is_unix())
        {
            if($this->program_path == "") $this->program_path = ":/usr/local/bin:/usr/local/sbin:/usr/bin:/bin:/usr/sbin:/sbin:";
            else $this->program_path = $pprogram_path;
            $this->on_unix();
        }
        parent::__construct($pprogram_path);
    }

    protected function execute($pparams = array())
    {
        try
        {
            if(preg_match("~^ *[A-z0-9\-\_\.]+ *$~im",$this->program))
            {
                $command = escapeshellcmd($this->program);
                $params = "";
                if(is_array($pparams) && count($pparams) >= 1)
                {
                    foreach($pparams as $key => $value )
                    {
                        if(is_string($value))
                        {
                            if(!preg_match("~^ *-*[A-z0-9]+ *$~im",$value)) $value = escapeshellarg($value);
                        }
                        if(is_int($key) || is_integer($key) || is_numeric($key))
                        {
                            $params .= " {$value} ";
                            continue;
                        }
                        if(is_string($key) && preg_match("~^ *-*[A-z0-9]+ *$~im",$key))
                        {
                            if($value && $value != false && $value != "")
                            {
                                $params .= " {$key} {$value} ";
                            } else
                            {
                                $params .= " {$key} ";
                            }
                        }
                    }
                }
                return shell_exec($command." {$params} 2>&1");
            }
            throw new exception("bad input to execute bash command");
        } 
        catch (exception $e) 
        {
            if(os::is_windows()) return "Invalid CMD Command";
            else return "Invalid Bash Command";
        }
    }

}

abstract class shell_program extends console_program {}
