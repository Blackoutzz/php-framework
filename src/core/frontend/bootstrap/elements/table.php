<?php
namespace core\frontend\bootstrap\elements;
use core\frontend\html\elements\table as dom_table;
use core\backend\database\dataset;
use core\backend\database\dataset_array;
use core\common\str;

/**
 * Bootstrap Element : Table
 *
 * Based of the RE:DOM ideas
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class table extends dom_table
{
    
    protected $caption = "";
    
    public function __construct($pdata = array(),$pattributes = array())
    {
        parent::__construct($pdata , $pattributes );
        $this->include_attribute("class" , "table");
    }
    
    public function set_dark_theme($pbool)
    {
        
    }
    
    public function set_responsive($presponsive)
    {
        
    }
    
    public function set_bordered($pborder)
    {
        
    }
    
    public function set_stripped($pstripped)
    {
        
    }
    
    public function set_caption($pcaption)
    {
        if(is_string($pcaption) && $pcaption != "")
            $this->caption = str::sanitize($pcaption);
        else
            $this->caption = ""; 
    }
    
}
