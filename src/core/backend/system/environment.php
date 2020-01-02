<?php
namespace core\backend\system;

/**
 * Environment
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 */

abstract class environment 
{

    static public function get_variable($pname)
    {
        if(preg_match("~^[A-z_]+$~im",$pname))
        {
            $output = "";
            if(os::is_unix())
                $output = shell_exec("echo \${$pname} 2>&1");
            elseif(os::is_windows())
                $output = shell_exec("\$env:{$pname} 2>&1");
            return trim($output);
        }
        return "";
    }

}
