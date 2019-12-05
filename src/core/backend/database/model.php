<?php
namespace core\backend\database;
use core\backend\database\connection;
use core\backend\database\mysql\dataset as mysql_dataset;
use core\common\str;

abstract class model extends reference
{

    protected $connection;

    public function __construct($pconnection)
    {
        if($pconnection instanceof connection)
        {
            $this->connection = $pconnection;
        }
    }

    protected function get_connection()
    {
        return $this->connection;
    } 

    protected function is_connected()
    {
        return (isset($this->connection) && $this->connection->is_connected());
    }

    protected function get_query_offset($pmax,$ppage)
    {
        return ($pmax*($ppage-1));
    }

    protected function parse_id(&$pid)
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
    
    protected function get_parsed_id($pid)
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

    protected function parse_boolean(&$pbool)
    {
        return $pbool = intval(($pbool === true || $pbool >= 1));
    }

    protected function get_parsed_boolean($pbool)
    {
        return (intval(($pbool === true || $pbool >= 1)));
    }

    protected function is_null($pvar)
    {
        if($pvar === NULL || $pvar === 0 || $pvar === "") return true;
        if($pvar instanceof mysql_dataset)
        {
            if($pvar->get_id() === 0 || $pvar->get_id() === NULL) return true;
        }
        return false;
    }

    protected function get_sanitized_string($pstring)
    {
       return str::sanitize($pstring);
    }

    protected function sanitize_string(&$pstring)
    {
       return ($pstring = str::sanitize($pstring));
    }

    protected function get_sanitized_integer($pinteger)
    {
        return intval($pinteger);
    }

    protected function sanitize_integer(&$pinteger)
    {
        return ($pinteger = intval($pinteger));
    }

}
