<?php
namespace core\frontend\components;
use core\component;

/**
 * Template
 * 
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class template extends component
{

    public function __toString()
    {
        return "";
    }

    protected function get_html()
    {
        //return new html();
    }

    protected function get_template()
    {
        //return new template();
    }

}
