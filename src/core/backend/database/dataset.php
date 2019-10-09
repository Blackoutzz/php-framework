<?php
namespace core\backend\database;
use core\component;
use core\common\str;
use core\program;

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

class dataset extends model
{
    
    public function __construct($pdata,$pvar_names = array())
    {
        $this->parse_data($pdata,$pvar_names);
    }
    
    protected function parse_data($pdata,$pvar_names = array())
    {
        if(is_array($pvar_names) && count($pvar_names) == 0)
        {
            if($pdata instanceof \stdClass || is_object($pdata))
            {
                foreach(get_object_vars($this) as $name => $value)
                {
                    if(isset($pdata->$name)) $this->$name = $pdata->$name;
                }
            } else {
                foreach(get_object_vars($this) as $name => $value)
                {
                    if(isset($pdata[$name])) $this->$name = $pdata[$name];
                }
            }
        } else {
            if($pdata instanceof \stdClass || is_object($pdata))
            {
                foreach($pvar_names as $data_name => $object_name)
                {
                    if(isset($pdata->$data_name)) $this->$object_name = $pdata->$data_name;
                }
            } elseif(is_array($pdata)) 
            {
                foreach($pvar_names as $data_name => $object_name)
                {
                    if(isset($pdata[$data_name])) $this->$object_name = $pdata[$data_name];
                }
            }
        }
    }

    public function parse_xml($pxml)
    {
        $structure = array();
        if(is_string($pxml))
        {
            $doc = new \DOMDocument;
            $doc->loadXML($pxml);
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
        } 
        return $structure;
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
                    if($node->childNodes->length >= 1)
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
        //TODO
    }

    protected function parse_csv_entry($pentry,$pdelimiter = ',')
    {
        //TODO
    }

    public function __toString()
    {
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            if($name === "name") return $value;
        }
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            if(is_string($value)) return $value;
        }
        return array_pop(explode("\\",__CLASS__));
    }

    public function __get($pname)
    {
        $method_name = "get_{$pname}";
        if(method_exists($this,$method_name)) return $this->$method_name();
        return NULL;
    }

    public function __set($pname,$pvalue)
    {
        $method_name = "set_{$pname}";
        if(method_exists($this,$method_name)) return $this->$method_name($pvalue);
        return NULL;
    }

    public function __toStdClass()
    {
        $object = new \stdClass();
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            if(method_exists($this,"is_".$name)) $function_name = "is_".$name;
            elseif(method_exists($this,"get_".$name)) $function_name = "get_".$name;
            if(isset($function_name) && $function_name != "")
                $value = $this->$function_name();
            else 
                $value = $this->$name;
            $object->$name = $this->parse_value($value);
        }
        return $object;
    }

    protected function parse_value(&$value)
    {
        if(is_object($value) && $value instanceof dataset)
        {
            return $value = $value->__toStdClass();
        } 
        elseif(is_object($value) && $value instanceof dataset_array)
        {
            return $value = $value->__toStdClass();
        }
        elseif(is_array($value))
        {
            $new_value = array();
            if(count($value) >= 1)
            {
                foreach($value as $key_value => $sub_value)
                {
                    $new_value[$key_value] = $this->parse_value($sub_value);
                }
            }
            return $value = $new_value;
        }
        else 
        {
            if(is_int($value) || is_float($value) || is_numeric($value) || is_integer($value))
                return $value = intval($value);
            elseif(is_object($value) && "{$value}" != "")
                return $value = "{$value}";
            elseif(is_string($value))
                return $value; 
            elseif(is_bool($value))
                return $value;
        }
        return $value = false;
    }

    public function __toJson()
    {
        return json_encode($this->__toStdClass());
    }

    public function __toCSV()
    {
        $csv = "";
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            $function_name = "get_".$name;
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

    public function __toHTML()
    {
        //TODO
    }

    public function __toArray()
    {
        return json_decode(json_encode($this->__toStdClass()),true);
    }

    protected function get_variables()
    {
        $variables = array("id"=>$this->id);
        $table_variables = get_object_vars($this);
        if(isset($table_variables["name"]) && $table_variables["name"] != NULL) $variables["name"] = $table_variables["name"];
        foreach($table_variables as $variable_name => $variable_value)
        {
            if($variable_name != "id" || $variable_name != "table_name" || $variable_name != "name")
            {
                $variables[$variable_name] = $variable_value;
            }
        }
        return $variables;
    }

    protected function get_variable_type($pvariable_name)
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