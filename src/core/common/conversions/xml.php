<?php
namespace core\common\conversions;
use core\common\exception;
use core\backend\database\dataset;
use core\backend\database\dataset_array;

/**
 * xml short summary.
 *
 * xml description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class xml
{
    public static function encode($pvar,$precursive = false)
    {
        if((is_array($pvar) || $pvar instanceof dataset_array) && count($pvar) >= 1)
        {
            $xml = "<array>".CRLF;
            foreach($pvar as $var)
            {
                if($var instanceof dataset)
                {
                    $sub_xml = explode(CRLF,$var->__toXML($precursive));
                    foreach($sub_xml as &$inner_xml)
                    {
                        $inner_xml = "\t".$inner_xml;
                    }
                    $xml .= implode(CRLF,$sub_xml).CRLF;
                }
            }
            return $xml.CRLF."</array>";
        } 
        elseif(is_object($pvar)) 
        {
            if($pvar instanceof dataset)
            {
                return $pvar->__toXML().CRLF;
            }
        }
        return false;
    }

}