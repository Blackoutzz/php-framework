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

abstract class void_element
{
    
    protected $tag;

    protected $attributes = array();

    public function __construct($ptag , $pattributes = array())
    {
        $this->tag = $ptag;
        $this->parse_attributes($pattributes);
    }

    public function __toString()
    {
        if(count($this->attributes) >= 1)
        {
            return "<{$this->tag}".$this->get_attributes_text()." />";
        } else {
            return "<{$this->tag} />";
        }
    }

}
