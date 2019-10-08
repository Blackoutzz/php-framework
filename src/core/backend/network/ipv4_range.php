<?php
namespace core\backend\network;
use core\common\regex;

/**
 * ip_range short summary.
 *
 * ip_range description.
 *
 * @version 1.0
 * @author la_ma
 */

class ipv4_range
{

    protected $min = "0.0.0.0";

    protected $max = "0.0.0.0";

    protected $netmask = "24";

    public function __construct($prange)
    {
        $range = trim($prange);
        if(regex::is_ipv4_range($range))
        {
            $match = regex::get_match(regex::ipv4_range,$range);
            $this->min = $match[1];
            $this->max = $match[2];
        }
    }

    public function __toString()
    {
        return $this->min."-".$this->max;
    }

    public function is_valid()
    {
        return ($this->min != "0.0.0.0" && $this->max != "0.0.0.0");
    }

    public function is_in_range($pip)
    {
        if(regex::is_ip($pip))
        {
            $min = regex::get_match(regex::ipv4,$this->min);
            $max = regex::get_match(regex::ipv4,$this->max);
            $ip  = regex::get_match(regex::ipv4,$pip);
            if($min[2] <= $ip[2] && $max[2] >= $ip[2])
            {
                if($min[3] <= $ip[3] && $max[3] >= $ip[3])
                {
                    if($min[4] <= $ip[4] && $max[4] >= $ip[4])
                    {
                        if($min[5] <= $ip[5] && $max[5] >= $ip[5])
                        {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

}