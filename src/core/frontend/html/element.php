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

abstract class element extends node
{
    
    protected $tag;

    protected $attributes = array();

    protected $inner_html = array();

    public function __construct($ptag , $pinner_html = array() , $pattributes = array())
    {
        $this->tag = $ptag;
        $this->parse_attributes($pattributes);
        $this->inner_html = $pinner_html;
    }

    public function get_inner_html()
    {
        $html = "\n";
        if(is_array($this->inner_html))
        {
            foreach($this->inner_html as $element)
            {
                $html .= "\t".$element."\n";
            }
            return $html;
        }
        if(is_string($this->inner_html))
        {
            $html .= "\t".$this->inner_html."\n";
        }
        return $html;
    }

    public function __toString()
    {
        if(count($this->attributes) >= 1)
        {
            return "<{$this->tag}".$this->get_attributes_text().">".$this->get_inner_html()."</{$this->tag}>";
        } else {
            return "<{$this->tag}>".$this->get_inner_html()."</{$this->tag}>";
        }
    }

}
