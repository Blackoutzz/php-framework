<?php
namespace core\common\components;

class stack extends exportable implements \Iterator , \ArrayAccess, \Countable
{
    
    protected $array;

    protected $position = 0;

    public function __construct($parray = array())
    {
        $this->array = $parray;
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
        if (is_null($offset))
        {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
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
        if(isset($this->array[$offset])) 
            return $this->array[$offset];
        else
            return false;
    }

    public function count()
    {
        return count($this->array);
    }

    public function get_size()
    {
        return $this->count();
    }

    public function __toStdClass()
    {
        $object = array();
        if(count($this->array) >=1)
        {
            foreach($this->array as $key => $value)
            {
                $object[$key] = $this->parse_stdclass_value($value);
            }
        }
        return $object;
    }

    public function __toJson()
    {
        return json_encode($this->__toStdClass());
    }

    public function __toCSV()
    {
        $csv = "";
        foreach(get_object_vars($this) as $name => $value)
        {
            $value = str_replace("\"","\"\"",$value);
            if($csv == "")
            {
                $csv .= "\"{$value}\"";
            } else {
                $csv .= ",\"{$value}\"";
            }
        }
        return $csv;
    }

    public function __toXML($precursive = false)
    {
        $xml = "<{$this->table_name} type=\"object\">".CRLF;
        foreach(get_object_vars($this) as $name => $value)
        {
            $function_name = "get_".$name;
            if(method_exists($this,$function_name)) $value = $this->$function_name();
            if(is_object($value) && $value instanceof exportable)
            {
                if($precursive)
                {
                    $sub_xml = explode(CRLF,$value->__toXML(true));
                    foreach($sub_xml as &$inner_xml)
                    {
                        $inner_xml = "\t".$inner_xml;
                    }
                    $xml .= implode(CRLF,$sub_xml).CRLF;
                    continue;
                } else {
                    $value = str_replace("\n","&#xD;",str_replace("\r","&#xA;",$value));
                    $xml .= "\t<{$name}>{$value}</{$name}>".CRLF;
                    continue;
                }
            } else {
                if(is_string($value)) $value = str_replace("\n","&#xD;",str_replace("\r","&#xA;",$value));
                if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value)) $value = intval($value);
                if(is_bool($value) && $value === true) $value = "1";
                if(is_bool($value) && $value === false) $value = "0";
                $xml .= "\t<{$name}>{$value}</{$name}>".CRLF;
                continue;
            }
        }
        $xml .= "</{$this->table_name}>";
        return $xml;
    }

    public function __toArray()
    {
        return $this->parse_array_value($this->array);
    }

}