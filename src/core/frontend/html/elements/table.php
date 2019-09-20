<?php
namespace core\frontend\html\elements;
use core\frontend\html\element;
use core\backend\database\dataset;
use core\backend\database\dataset_array;

/**
 * HTML Element : table
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class table extends element
{

    protected $data = array();

    public function __construct($pdata = array(),$pattributes = array())
    {
        $this->tag = "table";
        $this->parse_data($pdata);
    }

    protected function parse_data($pdata)
    {
        if($pdata instanceof dataset_array)
        {
            
        }
        elseif($pdata instanceof dataset)
        {
            
        }
    }

}
