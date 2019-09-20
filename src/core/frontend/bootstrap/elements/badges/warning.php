<?php
namespace core\frontend\bootstrap\elements\badges;
use core\frontend\bootstrap\elements\badge;

/**
 * Bootstrap Element : Warning Badge
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class warning extends badge
{

    public function __construct($pinner_html = array(),$pattributes = array())
    {
        parent::__construct($pattributes,$pinner_html);
        $this->include_attribute("class","badge-warning");
    }

}
