<?php
namespace core\backend\database\mysql;
use core\backend\database\dataset;
use core\common\sorting_order;

/**
 * Dataset Array
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class dataset_array implements \Iterator , \ArrayAccess, \Countable
{

    protected $position = 0;

    protected $array = array();

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
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    public function count()
    {
        return count($this->array);
    }

    public function order_by($pvariable_name = "name",$psorting_order = table_model_array_sorting_order::ascending_order)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    $new_array[$value] = $array_item;
                }
            }
        }
        if(sorting_order::ascending == $psorting_order) ksort($new_array);
        elseif(sorting_order::descending == $psorting_order) krsort($new_array);
        else return $this->array;

        $this->array = array_values($new_array);
        return $this->array;
    }

    public function get_ordered_by($pvariable_name = "name",$psorting_order = table_model_array_sorting_order::ascending_order)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    $new_array[$value] = $array_item;
                }
            }
        }
        if(sorting_order::ascending == $psorting_order) ksort($new_array);
        elseif(sorting_order::descending == $psorting_order) krsort($new_array);
        else return $this->array();
        return $new_array;
    }

    public function where($pvariable_name = "name",$pvalue)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    if($value === $pvalue) $new_array[$value] = $array_item;
                }
            }
        }
        $this->array = $new_array;
        return $this->array;
    }

    public function get_where($pvariable_name = "name",$pvalue)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    if($value === $pvalue) $new_array[$value] = $array_item;
                }
            }
        }
        return $new_array;
    }

    public function where_array($pvariables)
    {
        if(!is_array($pvariables)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $equal = false;
                foreach($pvariables as $variable_name => $variable_value)
                {
                    $method_name = "get_".$variable_name;
                    if(method_exists($array_item,$method_name))
                    {
                        $value = $array_item->$method_name();
                        if($value === $variable_value)
                        {
                            $equal = true;
                        } else {
                            $equal = false;
                        }
                    } else {
                        $equal = false;
                    }
                }
                if($equal === true) $new_array[] = $array_item;
            }
        }
        $this->array = $new_array;
        return $this->array;
    }

    public function get_where_array($pvariables)
    {
        if(!is_array($pvariables)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $equal = false;
                foreach($pvariables as $variable_name => $variable_value)
                {
                    $method_name = "get_".$variable_name;
                    if(method_exists($array_item,$method_name))
                    {
                        $value = $array_item->$method_name();
                        if($value === $variable_value)
                        {
                            $equal = true;
                        } else {
                            $equal = false;
                        }
                    } else {
                        $equal = false;
                    }
                }
                if($equal === true) $new_array[] = $array_item;
            }
        }
        return $new_array;
    }

    public function __toJson($precursive = false)
    {
        $object = new \stdClass();
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            if($name != "table_name")
            {
                $function_name = "get_".$name;
                if(method_exists($this,"is_".$name)) $function_name = "is_".$name;
                $value = $this->$function_name();
                if(is_object($value) && $value instanceof dataset)
                {
                    if($precursive) $object->$name = $value->__toJson($precursive);
                    else $object->$name = "{$value}";
                } else {
                    if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value)) $value = intval($value);
                    $object->$name = $value;
                }
            }
        }
        return $object;
    }

    public function __toCSV()
    {
        $csv = "";
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            $value = str_replace("\"","\"\"",$value);
            if($name != "table_name")
            {
                if($csv == ""){
                    $csv .= "\"{$value}\"";
                } else {
                    $csv .= ",\"{$value}\"";
                }
            }
        }
        return $csv;
    }

    public function __toXML($precursive = false)
    {
        $xml = "<{$this->table_name} type=\"object\">".CRLF;
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            if($name != "table_name")
            {
                $function_name = "get_".$name;
                if(method_exists($this,$function_name)) $value = $this->$function_name();
                if(is_object($value) && $value instanceof dataset)
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
        }
        $xml .= "</{$this->table_name}>";
        return $xml;
    }

    public function __toHTML()
    {
        
    }

    public function __toArray()
    {
        $array = array();
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            $array[$name] = $value;
        }
        return $array;
    }

    public function get_variables()
    {
        if($this->count() >= 1)
        {
            if(current($this->array) instanceof dataset) return current($this->array)->get_variables();
        }
        return array();
    }

    public function get_table_name()
    {
        if($this->count() >= 1)
        {
            if(current($this->array) instanceof dataset) return current($this->array)->get_table_name();
        }
        return "";
    }

    public function get_variable_type($pvariable_name)
    {
        if($pvariable_name == "name") return "string";
        if($pvariable_name == "integer") return "integer";
        if($pvariable_name == "table_name") return "string";
        $boolean_function = "is_{$pvariable_name}";
        $string_function = "get_{$pvariable_name}";
        if(method_exists($this,$boolean_function)) return "boolean";
        if(method_exists($this,$string_function))
        {
            if(is_string($this->$pvariable_name)) return "string";
            if(is_integer($this->$pvariable_name)
            || is_numeric($this->$pvariable_name)
            || is_float($this->$pvariable_name)
            || is_double($this->$pvariable_name)
            || is_int($this->$pvariable_name))
            {
                if(is_object($this->$string_function())) return "object";
                return "integer";
            }
            if(is_bool($this->$pvariable_name)) return "boolean";
            if(is_object($this->$pvariable_name)) return "object";
        }
        if(is_string($this->$pvariable_name)) return "string";
        if(is_integer($this->$pvariable_name)
        || is_numeric($this->$pvariable_name)
        || is_float($this->$pvariable_name)
        || is_double($this->$pvariable_name)
        || is_int($this->$pvariable_name)) return "integer";
        if(is_bool($this->$pvariable_name)) return "boolean";
        if(is_object($this->$pvariable_name)) return "object";
        return "null";
    }

}
