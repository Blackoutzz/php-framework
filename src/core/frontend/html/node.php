<?php
namespace core\frontend\html;
use core\common\str;

/**
 * HTML Element
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class node 
{

    protected $attributes = array();

    protected function parse_attributes($pattributes)
    {
        if(!is_array($pattributes)) $pattributes = array();
        if(is_array($pattributes) && count($pattributes) >= 1)
        {
            foreach($pattributes as $attribute_key => $attribute_value)
            {
                if(isset($this->attributes[$attribute_key]))
                {
                    if(!$this->has_attribute($attribute_key,$attribute_value))
                        $this->attributes[$attribute_key] .= " {$attribute_value}";
                } else {
                    $this->attributes[$attribute_key] = $attribute_value;
                }
            }
        } else {
            $this->attributes = array();
        }
    }

    public function get_attribute($pattribute)
    {
        if(isset($this->attributes[$pattribute])) return $this->attributes[$pattribute];
        return "";
    }

    public function has_attribute($pattribute)
    {
        if(isset($this->attributes[$pattribute])) return true;
        return false;
    }

    public function include_attribute($pkey,$pvalue)
    {
        if(!isset($this->attributes[$pkey]))
        {
            $this->attributes[$pkey] = $pvalue;
        }
        else 
        {
            if(!$this->has_attribute($pkey,$pvalue))
                $this->attributes[$pkey] .= " {$pvalue}";
        }
    }

    public function remove_attribute($pkey,$pvalue)
    {
        $key = strtolower($pkey);
        if(!isset($this->attributes[$key]))
        {
            $this->attributes[$key] = $pvalue;
        } 
        else 
        {
            if($this->has_attribute($key,$pvalue))
            {
                if($key === "src" || $key === "href")
                {
                    $this->attributes[$key] = "";
                }
                elseif($key === "class" || $key === "id")
                {
                    $this->attributes[$key] .= "";
                }
                elseif($key === "style")
                {
                    $this->attributes[$key] = trim(str::remove($this->attributes[$key],$pvalue));
                }
            }
        }
    }

    public function get_attributes_text()
    {
        $text = "";
        foreach($this->attributes as $attribute_key => $attribute_value)
        {
            $text .= " {$attribute_key}=\"{$attribute_value}\"";
        }
        return $text;
    }

}
