<?php
namespace core\frontend\bootstrap\elements\alerts;
use core\frontend\bootstrap\elements\alert;

/**
 * Bootstrap Element : Danger Alert
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/


class danger extends alert
{

    public function __construct($pinner_html = array(),$pattributes = array())
    {
        parent::__construct($pattributes,$pinner_html);
        $this->include_attribute("class","alert-danger");
    }

}
