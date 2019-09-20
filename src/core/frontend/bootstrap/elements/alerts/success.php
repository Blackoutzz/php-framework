<?php
namespace core\frontend\bootstrap\elements\alerts;
use core\frontend\bootstrap\elements\alert;

/**
 * Bootstrap Element : Success Alert
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class success extends alert
{

    public function __construct($pinner_html = array(),$pattributes = array())
    {
        parent::__construct($pattributes,$pinner_html);
        $this->include_attribute("class","alert-success");
    }

}