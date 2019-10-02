<?php
namespace core\backend\system;
use core\common\exception;

/**
 * console short summary.
 *
 * console description.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 */

abstract class console extends program
{

    public function __construct($pname,$ppath = "")
    {
        if(os::is_windows())
        {
            $this->path = $ppath;
            $this->on_windows();
        }
        elseif(os::is_unix() || os::is_macos())
        {
            if($this->path == "") $this->path = ":/usr/local/bin:/usr/local/sbin:/usr/bin:/bin:/usr/sbin:/sbin:";
            else $this->path = $ppath;
            $this->on_unix();
        }
        parent::__construct($pname,$ppath);
    }

    protected function execute($pparams = array())
    {
        try
        {
            if(preg_match("~^ *[A-z0-9\-\_\.]+ *$~im",$this->name))
            {
                $command = escapeshellcmd($this->name);
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