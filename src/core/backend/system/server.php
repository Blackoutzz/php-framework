<?php
namespace core\backend\system;
use core\common\exception;
use core\common\str;

/**
 * Server.
 *
 * Utility to get server information
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class server 
{

    static function get_ip()
    {
        return str::sanitize($_SERVER['SERVER_ADDR']);
    }

    static function get_domain()
    {
        return str::sanitize($_SERVER['HTTP_HOST']);
    }

    static function is_using_apache()
    {
        try
        {
            if(function_exists("apache_get_version") || function_exists("apache_get_modules"))
            {
                return true;
            }
            return false;
        }
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_using_cloudproxy()
    {
        try
        {
            if(isset($_SERVER))
            {
                if(isset($_SERVER["HTTP_X_SUCURI_CLIENTIP"]))
                {
                    return true;
                }
                if (preg_match('@cloudproxy@',gethostbyaddr(gethostbyname($_SERVER['HTTP_HOST'])))){
                    return true;
                }
                if (preg_match('@cloudproxy@',gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME'])))){
                    return true;
                }
                if (preg_match( '@cloudproxy@',gethostbyaddr(gethostbyname(php_uname("n"))))){
                    return true;
                }
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function is_using_nginx()
    {
        try
        {
            if(isset($_SERVER["SERVER_SOFTWARE"]))
            {
                if(preg_match('~nginx~im',$_SERVER["SERVER_SOFTWARE"]))
                {
                    return true;
                }
            }
            return false;
        }
        catch(exception $e)
        {
            return false;
        }

    }

    static function is_using_iis()
    {
        try
        {
            if(isset($_SERVER["SERVER_SOFTWARE"]))
            {
                if(preg_match('~microsoft-iis~im',$_SERVER["SERVER_SOFTWARE"]))
                {
                    return true;
                }
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function is_using_geoip()
    {
        try
        {
            if(isset($_SERVER))
            {
                foreach($_SERVER as $key => $content)
                {
                    if(preg_match('~geoip~im',$key))
                    {
                        return true;
                    }
                }
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function get_memory_limit()
    {
        try{
            if(function_exists("ini_get"))
            {
                return ini_get("memory_limit");
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_parameters()
    {
        try
        {
            if(isset($_SERVER))
            {
                return $_SERVER;
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function get_php_version()
    {
        try
        {
            if(function_exists("phpversion"))
            {
                return phpversion();
            }
            return false;
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function get_apache_version()
    {
        try
        {
            if(function_exists("apache_get_version"))
            {
                return apache_get_version();
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_apache_modules()
    {
        try 
        {
            if(function_exists("apache_get_modules"))
            {
                return apache_get_modules();
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function get_hostname()
    {
        try 
        {
            if(function_exists("php_uname"))
            {
                return php_uname("n");
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_os_name()
    {
        try
        {
            if(function_exists("php_uname"))
            {
                return php_uname("s");
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_os()
    {
        return new os();
    }

    static function get_system_informations()
    {
        try
        {
            if(function_exists("php_uname"))
            {
                return php_uname("m");
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_hosting_software()
    {
        try
        {
            if(isset($_SERVER["SERVER_SOFTWARE"]))
            {
                return $_SERVER["SERVER_SOFTWARE"];
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_hosting_provider()
    {
        try
        {
            if(function_exists("gethostbyaddr") && isset($_SERVER['SERVER_ADDR']))
            {
                $provider = gethostbyaddr($_SERVER['SERVER_ADDR']);
                if(false !== $provider )
                {
                    $providers = array();
                    $providers['secureserver.net'] = 'GoDaddy';
                    $providers['bluehost.com']     = 'BlueHost';
                    $providers['hostgator.com']    = 'HostGator';
                    $providers['site5.com']        = 'Site5';
                    $providers['amazonaws.com']    = 'Amazon';
                    $providers['siteground.com']   = 'Siteground';
                    $providers['gridserver.com']   = 'MediaTemple';
                    $providers['linode.com']       = 'WPEngine';
                    $providers['1e100.net']        = 'Google';
                    $providers['dreamhost.com']    = 'DreamHost';
                    foreach($providers as $host => $name)
                    {
                        if( false !== strpos( $provider, $host ) )
                        {
                            return $host;
                        }
                    }
                }
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

}
