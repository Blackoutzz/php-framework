<?php 
namespace core\common\components;
use core\component;

abstract class exportable extends parseable
{

    public function __serialize()
    {
        return $this->__toJson();
    }

    public function __unserialize($pdata)
    {
        if(is_string($pdata)) $data = json_decode($pdata);
        if($data instanceof \stdClass || is_object($data))
        {
            foreach(get_object_vars($this) as $name => $value)
            {
                if(isset($data->$name)) $this->$name = $data->$name;
            }
        } else {
            foreach(get_object_vars($this) as $name => $value)
            {
                if(isset($data[$name])) $this->$name = $data[$name];
            }
        }
    }
    
    public function __toStdClass()
    {
        $object = new \stdClass();
        if($array = $this->__toArray())
        {
            if(is_array($array) && count($array) >= 1)
            {
                foreach($array as $name => $value)
                {
                    $object->$name = $this->parse_value($value);
                }
            }
        }
        return $object;
    }

    public function __toJson()
    {
        return json_encode($this->__toArray());
    }

    public function __toCSV()
    {
        $csv = "";
        foreach($this->__toArray() as $name => $value)
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
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
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

    public function __toHTML()
    {
        //TODO
    }

    public function __toArray()
    {
        return get_object_vars($this);
    }

}
