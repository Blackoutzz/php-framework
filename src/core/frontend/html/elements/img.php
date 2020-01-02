<?php
namespace core\frontend\html\elements;
use core\frontend\html\void_element;

/**
 * HTML Element : img
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class img extends void_element
{

    public function __construct($pattributes = array())
    {
        $this->tag = "img";
    }

}
