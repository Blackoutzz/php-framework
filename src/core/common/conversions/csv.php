<?php
namespace core\common\conversions;
use core\exception;
use core\mvc\table_model;
use core\mvc\table_model_array;

/**
 * Object Conversion : CSV
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class csv
{

    public static function encode($pvar)
    {
        if((is_array($pvar) || $pvar instanceof table_model_array) && count($pvar) >= 1)
        {
            foreach($pvar as $var)
            {
                if($var instanceof table_model)
                {
                    $header = "";
                    $csv = "";
                    $item = $var->__toArray();
                    foreach($item as $var => $value)
                    {

                        if($var === "table_name") continue;
                        if($header == "")
                        {
                            $header = $var;
                        } else {
                            $header .= ",".$var;
                        }
                    }

                    foreach($pvar as $var)
                    {
                        $csv .= $var->__toCSV().CRLF;
                    }
                    return $header.CRLF.$csv;
                }
            }

        } elseif(is_object($pvar)) 
        {
            if($pvar instanceof table_model)
            {
                $header = "";
                $csv = "";
                $vars = get_object_vars($pvar);
                foreach($vars as $header => $value)
                {
                    $value = str_replace("\"","\"\"",$value);
                    if($header == "")
                    {
                        $header .= $header;
                    } else {
                        $header .= ",".$header;
                    }
                    if($csv == "")
                    {
                        $csv .= "\"{$value}\"";
                    } else {
                        $csv .= ",\"{$value}\"";
                    }
                }
                return $header.CRLF.$csv;
            }
        }
        return false;
    }

}