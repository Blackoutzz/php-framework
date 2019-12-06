<?php
namespace core\backend\database;
use core\common\components\exportable;

/**
 * Dataset
 * 
 * Morphable data model
 * 
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class dataset extends exportable
{
    
    public function __construct($pdata)
    {
        $this->__unserialize($pdata);
    }

    public function __toString()
    {
        $classname = explode("\\",get_class($this));
        return array_pop($classname);
    }

    public function __get($pname)
    {
        $method_name = "get_{$pname}";
        if(method_exists($this,$method_name)) return $this->$method_name();
        return false;
    }

    public function __set($pname,$pvalue)
    {
        $method_name = "set_{$pname}";
        if(method_exists($this,$method_name)) return $this->$method_name($pvalue);
        return false;
    }

    public function __toStdClass()
    {
        $object = new \stdClass();
        if($array = get_object_vars($this))
        {
            if(is_array($array) && count($array) >= 1)
            {
                foreach(get_object_vars($this) as $name => $value)
                {
                    if(method_exists($this,"is_".$name)) $function_name = "is_".$name;
                    elseif(method_exists($this,"get_".$name)) $function_name = "get_".$name;
                    if(isset($function_name) && $function_name != "")
                        $value = $this->$function_name();
                    else 
                        $value = $this->$name;
                    $object->$name = $this->parse_stdclass_value($value);
                }
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
            $function_name = "get_".$name;
            if(method_exists($this,"is_".$name)) $function_name = "is_".$name;
            if(method_exists($this,$function_name)) $value = $this->$function_name();
            $value = str_replace("\"","\"\"",$value);
            if(is_string($value)) $value = str_replace("\n","&#xD;",str_replace("\r","&#xA;",$value));
            if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value)) $value = "".intval($value);
            if(is_bool($value) && $value === true) $value = "true";
            if(is_bool($value) && $value === false) $value = "false";
            if($csv == ""){
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
        if($precursive)
        {
            foreach(array_reverse(get_object_vars($this),true) as $name => $value)
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
                } 
                else 
                {
                    if(is_string($value)) $value = str_replace("\n","&#xD;",str_replace("\r","&#xA;",$value));
                    if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value)) $value = "".intval($value);
                    if(is_bool($value) && $value === true) $value = "true";
                    if(is_bool($value) && $value === false) $value = "false";
                    $xml .= "\t<{$name}>{$value}</{$name}>".CRLF;
                    continue;
                }  
            }
        } else {
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
                        if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value)) $value = "".intval($value);
                        if(is_bool($value) && $value === true) $value = "true";
                        if(is_bool($value) && $value === false) $value = "false";
                        $xml .= "\t<{$name}>{$value}</{$name}>".CRLF;
                        continue;
                    }
                }
            }
        }
        $xml .= "</{$this->table_name}>";
        return $xml;
    }

    public function __toArray()
    {
        $object = array();
        if($array = get_object_vars($this))
        {
            if(is_array($array) && count($array) >= 1)
            {
                foreach(get_object_vars($this) as $name => $value)
                {
                    if(method_exists($this,"is_".$name)) $function_name = "is_".$name;
                    elseif(method_exists($this,"get_".$name)) $function_name = "get_".$name;
                    if(isset($function_name) && $function_name != "")
                        $value = $this->$function_name();
                    else 
                        $value = $this->$name;
                    $object[$name] = $this->parse_array_value($value);
                }
            }
        }
        return $object;
    }

}