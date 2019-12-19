<?php
namespace core\common\components;
use core\common\components\exportable;
use core\common\components\stackable;

class stack extends stackable
{

    public function __serialize()
    {
        return $this->__toArray();
    }

    public function __unserialize($pdata)
    {
        if(is_string($pdata))
        {
            $data = json_decode($pdata);
        }
        else $data = $pdata;
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

    public function __toHTML()
    {
        //TODO
    }
    
    protected function parse_array_value(&$value)
    {
        if(is_object($value))
        {
            if($value instanceof exportable) 
            {
                return $value = $value->__toArray();
            }
            else 
            {
                if(method_exists($value,"__toString")) 
                    return $value = "{$value}";
                else
                    return $value = get_object_vars($value);
            }
        }
        elseif(is_array($value))
        {
            $new_value = array();
            if(count($value) >= 1)
            {
                foreach($value as $key_value => $sub_value)
                {
                    $new_value[$key_value] = $this->parse_array_value($sub_value);
                }
            }
            return $value = $new_value;
        }
        else 
        {
            if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value))
                return $value = intval($value);
            elseif(is_string($value))
                return $value; 
            elseif(is_bool($value))
                return $value;
        }
        return $value = false;
    }

    protected function parse_stdclass_value(&$value)
    {
        if(is_object($value))
        {
            if($value instanceof exportable) 
            {
                return $value = $value->__toStdClass();
            }
            else 
            {
                if(method_exists($value,"__toString")) 
                    return $value = "{$value}";
                else
                    return $value = $this->parse_stdclass_value(get_object_vars($value));
            }
        } 
        elseif(is_array($value))
        {
            $new_value = array();
            if(count($value) >= 1)
            {
                foreach($value as $key_value => $sub_value)
                {
                    $new_value[$key_value] = $this->parse_stdclass_value($sub_value);
                }
            }
            return $value = $new_value;
        }
        else 
        {
            if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value))
                return $value = intval($value);
            elseif(is_string($value))
                return $value; 
            elseif(is_bool($value))
                return $value;
        }
        return $value = false;
    }

    protected function parse_variable_type($pvariable_name)
    {
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

    protected function parse_xml(&$xml)
    {
        try
        {
            $structure = array();
            if(is_string($xml))
            {
                $doc = new \DOMDocument;
                if($doc->loadXML($xml))
                {
                    $node_count = 0;
                    for($i=0;$i<$doc->childNodes->length;$i++)
                    {
                        $node = $doc->childNodes->item($i);
                        if($node->nodeType === 1)
                        {
                            if(isset($structure[$node->nodeName]))
                            {
                                $node_count++;
                                $structure[$node->nodeName.$node_count] = $this->parse_xml_node($node);
                            } else {
                                $structure[$node->nodeName] = $this->parse_xml_node($node);
                            } 
                        } 
                    }
                } else {
                    throw new exception("Impossible or incomplete xml file to parse");
                 }
            } 
            return $xml = $structure;
        }
        catch(exception $e)
        {
            return false;
        }
    }

    protected function parse_xml_node($pnode)
    {
        $structure = array();
        if ($pnode instanceof \DOMElement)
        {
            if($pnode->hasAttributes())
            {
                $node_count = 0;
                for($i=0;$i<$pnode->attributes->length;$i++)
                {
                    $attribute = $pnode->attributes->item($i);
                    if(isset($structure[$attribute->nodeName]))
                    {
                        $node_count++;
                        $structure[$attribute->nodeName.$node_count] = $attribute->nodeValue;
                    } else {
                        $structure[$attribute->nodeName] = $attribute->nodeValue;
                    }
                    
                }
            }
            if($pnode->hasChildNodes())
            {
                $node_count = 0;
                for($i=0;$i<$pnode->childNodes->length;$i++)
                {
                    $node = $pnode->childNodes->item($i);
                    if($node->hasChildNodes())
                    {
                        if($node->nodeName == "#text" && trim($node->nodeValue) == "")
                            continue;
                        elseif($node->nodeName == "#text")
                            $structure[] = $this->parse_xml_node($node);
                        else 
                        {
                            if(isset($structure[$node->nodeName]))
                            {
                                $node_count++;
                                $structure[$node->nodeName.$node_count] = $this->parse_xml_node($node);
                            } else {
                                $structure[$node->nodeName] = $this->parse_xml_node($node);
                            }
                            
                        }      
                    } else {
                        if($node->nodeName == "#text" && trim($node->nodeValue) == "")
                            continue;
                        elseif($node->nodeName == "#text")
                            $structure = $node->nodeValue;
                        else {
                            if(isset($structure[$node->nodeName]))
                            {
                                $node_count++;
                                $structure[$node->nodeName.$node_count] = $this->parse_xml_node($node);
                            } else {
                                $structure[$node->nodeName] = $this->parse_xml_node($node);
                            }
                        }
                    }
                }
            }
            
        } elseif($pnode instanceof \DOMComment)
        {
            $structure = $pnode->nodeValue;
        }
        return $structure;
    }

    public function parse_csv($pcsv,$pdelimiter = ',')
    {
        try
        {
            $csv = new str($pcsv);
            $csv_lines = $csv->get_lines();
            $csv = array();
            $csv_titles = $this->parse_csv_title($csv_lines[0]);
            
            for($i=1;$i<count($csv_lines);$i++)
            {
                $entry = $this->parse_csv_entry($csv_lines[$i]);
                $stack = array();
                foreach($csv_titles as $id => $title)
                {
                    $stack[$title] = $entry[$id]; 
                }
                $csv[] = $stack;
            }
            return $csv;
        }
        catch (\exception $e)
        {
            return array();
        }
    }

    protected function parse_csv_title($pentry,$pdelimiter = ',')
    {
        return explode($pdelimiter,$pentry);
    }

    protected function parse_csv_entry($pentry,$pdelimiter = ',')
    {
        $data = explode($pdelimiter,$pentry);
        foreach($data as &$entry)
        {
            $entry = trim($entry,'"');
        }
        return $data;
    }

}
