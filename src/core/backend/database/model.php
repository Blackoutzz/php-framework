<?php
namespace core\backend\database;
use core\backend\database\connection;
use core\backend\database\mysql\dataset as mysql_dataset;
use core\common\str;


abstract class model extends reference
{

    static $connection;

    public function __construct($pconnection)
    {
        if($pconnection instanceof connection)
        {
            self::$connection = $pconnection;
        }
    }

    static public function get_connection()
    {
        return self::$connection;
    } 

    static public function is_connected()
    {
        return (isset(self::$connection) && self::$connection->is_connected());
    }

    static public function get_query_offset($pmax,$ppage)
    {
        return ($pmax*($ppage-1));
    }

    static public function parse_id(&$pid)
    {
        if(is_numeric($pid) || is_long($pid) || is_integer($pid))
        {
            return intval($pid);
        } 
        if(is_string($pid) && preg_match("~^ *([0-9]+) *$~im",$pid))
        {
            $pid = intval(trim($pid));
            return $pid;
        } 
        if($pid instanceof mysql_dataset)
        {
            $pid = $pid->get_id();
            return $pid;
        } 
        return NULL;
    }

    static public function get_parsed_id($pid)
    {
        if(is_numeric($pid) || is_long($pid) || is_integer($pid))
        {
            return intval($pid);
        } 
        if(is_string($pid) && preg_match("~^ *([0-9]+) *$~im",$pid))
        {
            return intval(trim($pid));
        } 
        if($pid instanceof mysql_dataset)
        {
            return $pid->get_id();
        } 
        return NULL;
    }

    static public function parse_boolean(&$pbool)
    {
        return $pbool = intval(($pbool === true || $pbool >= 1));
    }

    static public function get_parsed_boolean($pbool)
    {
        return (intval(($pbool === true || $pbool >= 1)));
    }

    static public function is_null($pvar)
    {
        if($pvar === NULL || $pvar === 0 || $pvar === "") return true;
        if($pvar instanceof mysql_dataset)
        {
            if($pvar->get_id() === 0 || $pvar->get_id() === NULL) return true;
        }
        return false;
    }

    static public function get_sanitized_string($pstring)
    {
       return str::sanitize($pstring);
    }

    static public function sanitize_string(&$pstring)
    {
       return ($pstring = str::sanitize($pstring));
    }

    static public function get_sanitized_integer($pinteger)
    {
        return intval($pinteger);
    }

    static public function sanitize_integer(&$pinteger)
    {
        return ($pinteger = intval($pinteger));
    }

}
