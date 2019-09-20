<?php
namespace core\frontend\bootstrap\elements;
use core\frontend\html\element;

/**
 * Bootstrap Element : Alert
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class alert extends element
{

    public function __construct($pinner_html = array(),$pattributes = array())
    {
        parent::__construct("div",$pattributes,$pinner_html);
        $this->include_attribute("class","alert");
    }

}
