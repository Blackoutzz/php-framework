<?php
namespace core\frontend\components;
use core\backend\database\dataset;

/**
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class widget extends dataset
{

    public function __toString()
    {
        return $this->__toHtml();
    }

    public function __toHtml()
    {
        //override this to print
        return "<pre>".print_r($this->__toArray())."</pre>";
    }

}
