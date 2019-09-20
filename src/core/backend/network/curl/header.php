<?php
namespace core\backend\network\curl;

/**
 * header data container class with minimal verification
 *
 * header description.
 *
 * @version 1.0
 * @author la_ma
 */

class header
{

    protected $key;

    protected $value;

    public function __construct($pheader)
    {
        if(is_string($pheader))
        {
            # here a verification through a regex is done to see if it actually is a header and an array
            # 
            if(preg_match("~^([^:]+)[:](.*)$~m",$pheader,$array))
            {
                $this->key = $array[1];
                $this->value = $array[2];
            }
        }
        #if the data given to the constructor is an array we skip verification
        elseif(is_array($pheader))
        {
            foreach($pheader as $key => $value)
            {
                $this->key = $key;
                $this->value = $value;
            }
        }
    }

    public function __toString()
    {
        return $this->key.": ".$this->value;
    }

    public function is_valid()
    {
        return ($this->key != "");
    }

    public function get_key()
    {
        return $this->key;
    }

    public function get_value()
    {
        return $this->value;
    }

}