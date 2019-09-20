<?php
namespace core\common\conversions;
use core\exception;
use core\backend\database\dataset;

/**
 * Object Conversion : JSON
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class json
{

	public static function encode($pvar,$precursive = false,$pdepth = 512)
	{
        try
        {
            if(is_array($pvar) || $pvar instanceof \Traversable)
            {
                $array = array();
                foreach($pvar as $item)
                {
                    if(is_object($item))
                    {
                        if($item instanceof dataset)
                        {
                            $array[] = $item->__toJson($precursive);
                        } else {
                            $vars = get_object_vars($item);
                            $sub_object = new \stdClass();
                            foreach($vars as $var_name => $var_value)
                            {
                                $sub_object->$var_name = $var_value;
                            }
                            $array[] = $sub_object;
                            continue;
                        }
                    }
                }
                $pvar = $array;
            } else {
                if(is_object($pvar))
                {
                    if($pvar instanceof dataset)
                    {
                        $pvar =$pvar->__toJson($precursive);
                    } else {
                        $vars = get_object_vars($pvar);
                        $sub_object = new \stdClass();
                        foreach($vars as $var_name => $var_value)
                        {
                            $sub_object->$var_name = $var_value;
                        }
                        $pvar = $sub_object;
                    }
                }
            }
            if($encoded = json_encode($pvar,0,$pdepth))
            {
                return $encoded;
            } elseif(json_last_error()) {
                throw new exception(json_last_error_msg());
            }
            return false;
        }
        catch (exception $e) {
            return false;
        }
	}

	public static function decode($pjson, $pis_assoc = false, $pdepth = 512)
	{
        try
        {
            if($decoded = json_decode($pjson,$pis_assoc,$pdepth))
            {
                return $decoded;
            } elseif(json_last_error()) {
                throw new exception(json_last_error_msg());
            }
            return false;
        }
        catch (exception $e) {
            return false;
        }
	}

}