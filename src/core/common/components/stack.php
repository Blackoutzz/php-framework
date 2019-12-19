<?php
namespace core\common\components;
use core\common\components\exportable;
use core\common\components\stackable;

class stack extends stackable
{

    public function __toStdClass()
    {
        $object = array();
        if(count($this->array) >= 1)
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
