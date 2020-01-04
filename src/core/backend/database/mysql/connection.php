<?php
namespace core\backend\database\mysql;
use core\common\exception;
use core\common\str;
use core\backend\components\filesystem\file;
use core\backend\database\connection as database_connection;
use core\backend\database\mysql\dataset_array;
use core\backend\database\mysql\dataset;
use \mysqli;
use core\program;

/**
 * Dataset Array
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class connection extends database_connection 
{

    protected $last_id;

    public function __construct($phost = 'localhost',$pport = 3306,$puser = 'root',$ppassword = 'admin',$pdb = 'app')
    {
        parent::__construct($phost,$pport);
        $this->connect($puser,$ppassword,$pdb);
    }

    public function get_last_id()
    {
        return $this->last_id;
    }

    public function connect($puser,$ppassword,$pdb)
    {
        $this->handler = new mysqli($this->host,$puser,$ppassword,$pdb,$this->port);
        if($this->handler->connect_errno > 0 && $this->handler->connect_error != NULL) throw new exception(str::get_utf8($this->handler->connect_error),$this->handler->connect_errno);
        if(isset($this->handler->errno) > 0 && $this->handler->error != NULL) throw new exception(str::get_utf8($this->handler->error),$this->handler->errno);
    }

    public function is_connected()
    {
        return ($this->handler->connect_errno == 0 && $this->handler->connect_errno > 0);
    }

    public function get_select_query($pquery)
    {
        try
        {
            $result = $this->handler->query($pquery);
            if(is_object($result))
            {
                $output = new dataset_array();
                while ($row = $result->fetch_assoc())
                {
                    $output[] = $row;
                }
                return $output;
            } else {
                if($result === true) return true;
                throw new exception($this->handler->error,$this->handler->errno);
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    public function get_multiple_select_queries($queries)
    {
        try
        {
            if($result = $this->handler->multi_query($queries))
            {
                $output = new dataset_array();
                while($this->handler->next_result())
                {
                    $output[] = $this->handler->use_result();
                }
                return $output;
            } 
            throw new exception($this->handler->error,$this->handler->errno);
        }
        catch (exception $e)
        {
            return false;
        }
    }

    public function get_prepared_select_query($request,$param_types = false,$pparams = false)
    {
        try
        {
            if(!is_null($this->handler)) $prepared = $this->handler->prepare($request);
            else $prepared = false;
            if(!$prepared) throw new exception($this->handler->error,$this->handler->errno);
            if($pparams !== false && is_array($pparams) && is_string($param_types)){
                $params = array();
                foreach($pparams as $key => &$value)
                {
                    $params[$key] = &$pparams[$key];
                }
                call_user_func_array(array($prepared,"bind_param"),array_merge(array($param_types),$params));
            }
            $prepared->execute();
            if($prepared->error !== false && $prepared->errno !== 0)
            {
                throw new exception($prepared->error,$prepared->errno);
            } else {
                $output = new dataset_array();
                $result = $prepared->get_result();
                while ($row = $result->fetch_assoc()) {
                    $output[] = $row;
                }
                $prepared->close();
                return $output;
            }
        }
        catch (exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_insert_query($pquery)
    {
        try
        {
            if(is_string($pquery))
            {
                if($this->handler->query($pquery))
                {
                    $this->last_id = $this->handler->insert_id;
                    return true;
                } else {
                    throw new exception($this->handler->error,$this->handler->errno);
                }
            } else {
                throw new exception($this->handler->error,$this->handler->errno);
            }
        }
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_prepared_insert_query($request,$param_types = false,$pparams = false)
    {
        try
        {
            if(!is_null($this->handler)) $prepared = $this->handler->prepare($request);
            else $prepared = false;
            if(!$prepared) throw new exception("Impossible to prepare SQL request : {$request}");
            if($pparams !== false && is_array($pparams) && is_string($param_types))
            {
                $params = array();
                foreach($pparams as $key => &$value)
                {
                    $params[$key] = &$pparams[$key];
                }
                call_user_func_array(array($prepared,"bind_param"),array_merge(array($param_types),$params));
            }
            $prepared->execute();
            if($prepared->error !== false && $prepared->errno !== 0) throw new Exception("Error: {$prepared->error} , Request: [{$request}]");
            if($prepared->insert_id !== 0 && $prepared->insert_id !== false && $prepared->insert_id !== -1)
            {
                $this->last_id = $prepared->insert_id;
                $prepared->close();
                return true;
            } else {
                $prepared->close();
                throw new Exception("Error: Nothing inserted , Request: [{$request}]");
            }
        }
        catch (exception $e)
        {
            var_dump($this->handler);
            return false;
        }
    }

    public function get_prepared_update_query($request,$param_types = false,$pparams = false)
    {
        try
        {
            if(!is_null($this->handler)) $prepared = $this->handler->prepare($request);
            else $prepared = false;
            if(!$prepared) throw new exception("Impossible to prepare SQL request : {$request}");
            if($pparams !== false && is_array($pparams) && is_string($param_types))
            {
                $params = array();
                foreach($pparams as $key => &$value)
                {
                    $params[$key] = &$pparams[$key];
                }
                //$prepared->bind_param($param_types,$pparams["id"]); // Working.
                call_user_func_array(array($prepared,"bind_param"),array_merge(array($param_types),$params));
            }
            $prepared->execute();
            if($prepared->error !== false && $prepared->errno !== 0)
            {
                throw new exception("Error: {$prepared->error} , Request: [{$request}]");
            } else {
                if($prepared->affected_rows !== -1)
                {
                    $prepared->close();
                    return true;
                } else {
                    $prepared->close();
                    throw new exception("Error: Nothing Updated , Request: [{$request}]");
                }
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    public function execute_script($pfilepath)
    {
        $script = new file(program::$path.$pfilepath,false);
        if($script->exist() && $script->get_extension() === "sql")
        {
            return $this->get_multiple_select_queries($script->get_contents());
        }
        return false;
    }

    public function sanitize_query(&$pquery)
    {
        return ($pquery = $this->handler->real_escape_string($pquery));
    }

    public function get_sanitized_query($pquery)
    {
        return $this->handler->real_escape_string($pquery);
    }

    public function get_query_offset($pmax,$ppage)
    {
        return ($pmax*($ppage-1));
    }

    public function parse_id(&$pid)
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
        if($pid instanceof dataset)
        {
            $pid = $pid->get_id();
            return $pid;
        } 
        return NULL;
    }

    public function get_parsed_id($pid)
    {
        return $this->parse_id($pid);
    }

    public function parse_boolean(&$pbool)
    {
        return $pbool = intval(($pbool === true || $pbool >= 1));
    }

    public function get_parsed_boolean($pbool)
    {
        return (intval(($pbool === true || $pbool >= 1)));
    }

    public function parse_string(&$pstring)
    {
        return ($pstring = $this->handler->real_escape_string("{$pstring}"));
    }

    public function get_parsed_string($pstring)
    {
        return $this->handler->real_escape_string("{$pstring}");
    }

    public function is_null($pvar)
    {
        if($pvar === NULL || $pvar === 0 || $pvar === "") return true;
        if($pvar instanceof dataset)
        {
            if($pvar->get_id() === 0 || $pvar->get_id() === NULL) return true;
        }
        return false;
    }

}
