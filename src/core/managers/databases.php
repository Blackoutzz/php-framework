<?php
namespace core\managers;
use core\manager;
use core\backend\components\database;
use core\backend\components\databases\mysql;
use core\backend\components\databases\redis;
use core\backend\components\databases\json;
use core\common\regex;

class databases extends manager
{

    protected $array = array();
    
    protected $position = 0;

    public function __construct($pcomponent = array())
    {
        $this->array["mysql"] = array();
        $this->array["redis"] = array();
        $this->array["mongo"] = array();
        $this->array["json"] = array();
        $this->array["csv"] = array();
        $this->array["xml"] = array();

        if($pcomponent instanceof database)
        {
            if($pcomponent instanceof mysql)
            {
                $this->array["mysql"][] = $pcomponent;
            }
            elseif($pcomponent instanceof redis)
            {
                $this->array["redis"][] = $pcomponent;
            }
            elseif($pcomponent instanceof json)
            {
                $this->array["json"][] = $pcomponent;
            }
        } 
        elseif(is_array($pcomponent) && count($pcomponent) >=1)
        {
            foreach($pcomponent as $component)
            {
                if($component instanceof mysql)
                {
                    $this->array["mysql"][] = $component;
                }
                elseif($component instanceof redis)
                {
                    $this->array["redis"][] = $component;
                }
                elseif($component instanceof json)
                {
                    $this->array["json"][] = $component;
                }
            }
        }
    }

    public function get_mysql_databases()
    {
        
    }

    public function get_mysql_database_by_id($pid=0)
    {
        try
        {
            if(is_array($this->array["mysql"]) && count($this->array["mysql"]))
            {
                $id = intval($pid);
                if(isset($this->array["mysql"][$id]))
                {
                    return $this->array["mysql"][$id];
                }
                throw new exception("No mysql database found at id:{$id}");
            }
            throw new exception("No mysql database found");
        }
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_mysql_database_by_name($pname)
    {
        try
        {
            if(is_array($this->array["mysql"]) && count($this->array["mysql"]))
            {
                $name = "{$pname}";
                if(regex::is_slug($pname))
                {
                    foreach($this->array["mysql"] as $database)
                    {
                        if($database->get_name() === $name) return $database;
                    }
                    throw new exception("No mysql database named:{$name}");
                }
                throw new exception("Impossible to identify database name");
            }
            throw new exception("No mysql database found");
            
        }
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_redis_databases()
    {

    }

    public function get_redis_database_by_id($pid=0)
    {
        try
        {
            if(is_array($this->array["redis"]) && count($this->array["redis"]))
            {
                $id = intval($pid);
                if(isset($this->array["redis"][$id]))
                {
                    return $this->array["redis"][$id];
                }
                throw new exception("No redis database found at id:{$id}");
            }
            throw new exception("No redis database found");
        }
        catch(exception $e)
        {
            return false;
        }
    }

    public function has_database()
    {
        return (count($this->array["mysql"]) >= 1 || count($this->array["redis"]) >= 1 || count($this->array["json"]) >= 1);
    }

    public function has_mysql_database()
    {
        return (count($this->array["mysql"]) >= 1);
    }

    public function has_json_database()
    {
        return (count($this->array["json"]) >= 1);
    }

    public function has_xml_database()
    {
        return (count($this->array["xml"]) >= 1);
    }

    public function has_csv_database()
    {
        return (count($this->array["csv"]) >= 1);
    }

    public function has_redis_database()
    {
        return (count($this->array["redis"]) >= 1);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->array[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    public function offsetSet($offset, $value)
    {
        if($value instanceof database)
        {
            if($value instanceof mysql)
            {
                $this->array["mysql"][] = $value;
            }
            elseif($value instanceof redis)
            {
                $this->array["redis"][] = $value;
            }
            elseif($value instanceof json)
            {
                $this->array["json"][] = $value;
            }
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    public function count()
    {
        return count($this->array);
    }


}