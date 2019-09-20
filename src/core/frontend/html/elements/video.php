<?php
namespace core\frontend\html\elements;
use core\frontend\html\element;

/**
 * HTML Element : Video
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class video extends element
{

    public function __construct($pinner_html = array(),$pattributes = array())
    {
        $this->tag = "video";
        $this->inner_html = $pinner_html;
        $this->attributes = $pattributes;
    }

}
