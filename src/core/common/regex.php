<?php
namespace core\common;
use core\exception;

/**
 * static regex short summary.
 *
 * static regex description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class regex 
{

    const ipv4 = '^(([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3}))(?::[0-9]{1,5})?$';

    const ipv4_range = '^([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})(?:-([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})|-([0-9]{1,3})|\/([0-9]{1,2}))$';

    const ipv6 = '^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$';

    const domain = '^(?:http[s]?:\/\/)?(?:\w\w\w\.)?([a-zA-Z0-9][a-zA-Z0-9.-]*[.][a-zA-Z][a-zA-Z]+)(?:[^ ]+)?$';

    const filename_extension = '^[A-z1-9\-\_\.\,]\.([^pPhH]{3})$';

    const filepath = '';

    const foldername = '^([^\\/:?*|\'"<>]+)$';

    const filename = '^([^\\/:?*|\'"<>]+)$';

    const slug = '^[A-Za-z0-9_-]+$';

    const numeric = '^(\-?[0-9]+\.[0-9]+|\-?[0-9]+|\-?[0-9]+\,[0-9]+)$';

    const mysql_timestamp = "^(([0-9]{4})-([0-2][0-9])-([0-3][0-9]) ([0-2][0-9]):([0-6][0-9]):([0-6][0-9]))$";

    const curl = '^ *(curl .+) *$';

    const hex = '(\\x[0-9A-Fa-f]{2}|[0-9A-Fa-f]{2})';

    const controller_view = '^([A-Za-z0-9\-\_]+)/([A-Za-z0-9\-\_]+)$';

    public static function is_matching($pregex,$pinput,$pflags ="im")
    {
        try
        {
            return preg_match("~{$pregex}~{$pflags}",$pinput);
        }
        catch (exception $e)
        {
            return false;
        }

    }

    public static function get_match($pregex,$pinput,$pflags = "im")
    {
        try
        {
            if(preg_match("~{$pregex}~{$pflags}",$pinput,$output)){
                return $output;
            }
            throw new exception("No matches found for input using regex.");
        }
        catch (exception $e)
        {
            return false;
        }
    }

    public static function get_matches($pregex,$pinput,$pflags = "im")
    {
        try
        {
            if(preg_match_all("~{$pregex}~{$pflags}",$pinput,$output)){
                return $output[0];
            }
            throw new exception("No matches found for input using regex.");
        }
        catch (exception $e)
        {
            return false;
        }
    }

    public static function is_ip($pip)
    {
        if(self::is_matching(self::ipv4,$pip) || self::is_matching(self::ipv6,$pip)) return true;
        return false;
    }

    public static function is_ipv4_range($prange)
    {
        return (self::is_matching(self::ipv4_range,$prange));
    }

    public static function is_slug($pslug)
    {
        return self::is_matching(self::slug,$pslug);
    }

    public static function is_numeric($pint)
    {
        return self::is_matching(self::numeric,$pint);
    }

    public static function is_mysql_timestamp($ptimestamp)
    {
        return self::is_matching(self::mysql_timestamp,$ptimestamp);
    }

    public static function is_curl($pcurl)
    {
        return self::is_matching(self::curl,$pcurl);
    }

    public static function is_filename($pfilename)
    {
        return self::is_matching(self::filename,$pfilename);
    }

    public static function is_foldername($pfoldername)
    {
        return self::is_matching(self::foldername,$pfoldername);
    }

    public static function is_controller_view($pcontroller_view)
    {
        return self::is_matching(self::controller_view,$pcontroller_view);
    }

    public static function is_domain($pdomain)
    {
        return self::is_matching(self::domain,$pdomain);
    }

}