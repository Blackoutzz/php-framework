<?php
namespace core\common;
use core\common\charsets\ascii;
use core\exception;
use core\common\regex as sregex;
use core\common\components\regex;

/**
 * Static String Library
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class str
{

    const chars = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","y","Y","z","Z","x","X");

    const numbers = array("1","2","3","4","5","6","7","8","9","0");

    const specials =  array("`","~","!","@","#","$","%","^","&","*","(",")","-","_","+","=","[","]","{","}",";",":",'"',"'",",","<",".",">","?","/","\\","|","æ","ß","ª","ƒ","©","∆","˚","»","¢","√","∫"," ","µ","œ","∑","∂","¶","™","¥","π","ø","“","¡","£","€","∞","¬");

    const accents = array("é","É","È","è","î","Î","à","À","â","ä","Ä","Ï","ï","ë","Ë","Ç","ç");

    static function get_length($pstring)
    {
        try
        {
            if(is_string($pstring))
            {
                return strlen($pstring);
            } else{
                throw new exception("This is not a string.");
            }
        } 
        catch (exception $e)
        {
            return 0;
        }
    }

    static function remove($pstring,$premove)
    {
        try
        {
            if(is_string($pstring))
            {
                return str_replace($premove,"",$pstring);
            } else{
                throw new exception("This is not a string.");
            }
        }
        catch (exception $e)
        {
            return $pstring;
        }
    }

    static function replace($pstring,$preplace,$replaced = " ")
    {
        try
        {
            if(is_string($pstring))
            {
                return str_replace($preplace,$replaced,$pstring);
            } else{
                throw new exception("This is not a string.");
            }
        }
        catch (exception $e)
        {
            return $pstring;
        }
    }

    static function is_sanitized($pstring)
    {
        try
        {
            if(is_string($pstring))
            {
                $dangerous_chars = "<>\"'&";
                return preg_match("~{$dangerous_chars}~im",$pstring);
            }
            throw new exception("This is not a string.");
        } 
        catch(exception $e) 
        {
            return true;
        }
    }

    static function sanitize($pstring)
    {
        try
        {
            if(is_string($pstring))
            {
                return htmlspecialchars($pstring,ENT_QUOTES);
            }
            throw new exception("This is not a string.");
        } 
        catch(exception $e)
        {
            return $pstring;
        }

    }

    static function get_lines($pstring)
    {
        try
        {
            if(is_string($pstring))
            {
                return explode("\n",$pstring);
            }
            throw new exception("This is not a string.");
        } 
        catch(exception $e)
        {
            return array();
        }
    }

    static function get_line_by_id($pstring,$pid)
    {
        try
        {
            $id = intval($pid);
            if(is_string($pstring))
            {
                $lines = explode("\n",$pstring);
                if(count($lines) >= $id)
                {
                    return $lines[$id - 1];
                }
                throw new exception("Line {$id} doesn't exist.");
            }
            throw new exception("Impossible to find lines into this string.");
        } 
        catch(exception $e)
        {
            return "";
        }

    }

    static function compress($pstring)
    {
        try
        {
            if(is_string($pstring))
            {
                if(function_exists("gzdeflate"))
                {
                    return gzdeflate($pstring);
                }
                if(function_exists("gzcompress"))
                {
                    return gzcompress($pstring);
                }
                if(function_exists("gzencode"))
                {
                    return gzencode($pstring);
                }
                throw new exception("Impossible to compress.");
            }
            throw new exception("This is not a string.");
        } 
        catch (exception $e)
        {
            return $pstring;
        }

    }

    static function uncompress($pstring)
    {
        try
        {
            if(is_string($pstring))
            {
                if(function_exists("gzinflate"))
                {
                    return gzinflate($pstring);
                }
                if(function_exists("gzuncompress"))
                {
                    return gzuncompress($pstring);
                }
                if(function_exists("gzdecode"))
                {
                    return gzdecode($pstring);
                }
            }
            throw new Exception("Impossible to uncompress.");
        } 
        catch (Exception $e)
        {
            return $pstring;
        }
    }

    static function output($pdata,$pjson = false)
    {
        try
        {
            if(is_string($pdata))
            {
                print($pdata);
                return true;
            }
            if(is_integer($pdata) || is_numeric($pdata))
            {
                print($pdata);
                return true;
            }
            if(is_array($pdata))
            {
                if($pjson)
                {
                    print(json_encode($pdata));
                } else {
                    print_r($pdata);
                }
                return true;
            }
            if(is_object($pdata))
            {
                if($pjson)
                {
                    print(json_encode($pdata));
                } else {
                    print_r($pdata);
                }
                return true;
            }
            if(is_bool($pdata))
            {
                if($pdata === true)
                    print("1");
                if($pdata === false)
                    print("0");
                return true;
            }
            return false;
        } 
        catch (Exception $e)
        {
            return false;
        }
    }

    static function write($pdata,$psanitized = false)
    {
        try
        {
            if(is_string($pdata))
            {
                if($psanitized) $pdata = sstr::sanitize($pdata);
                echo($pdata);
                return true;
            }
            if(is_integer($pdata) || is_numeric($pdata))
            {
                echo($pdata);
                return true;
            }
            if(is_array($pdata))
            {
                foreach($pdata as $data)
                {
                    self::write($data,$psanitized);
                }
                return true;
            }
            if(is_object($pdata))
            {
                $pdata = json_decode(json_encode($pdata),true);
                foreach($pdata as $data)
                {
                    self::write($data,$psanitized);
                }
                return true;
            }
            if(is_bool($pdata))
            {
                if($pdata === true)
                    echo("1");
                if($pdata === false)
                    echo("0");
                return true;
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function writeline($pdata,$psanitized = false)
    {
        try
        {
            if(is_string($pdata))
            {
                if($psanitized) $pdata = sstr::sanitize($pdata);
                echo($pdata."\n");
                return true;
            }
            if(is_integer($pdata) || is_numeric($pdata))
            {
                echo($pdata."\n");
                return true;
            }
            if(is_array($pdata))
            {
                foreach($pdata as $data)
                {
                    self::writeline($data,$psanitized);
                }
                return true;
            }
            if(is_object($pdata))
            {
                $pdata = json_decode(json_encode($pdata),true);
                foreach($pdata as $data)
                {
                    self::writeline($data,$psanitized);
                }
                return true;
            }
            if(is_bool($pdata))
            {
                if($pdata === true)
                    echo("1\n");
                if($pdata === false)
                    echo("0\n");
                return true;
            }
            return false;
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function contains($pstring,$pregex,$pstrict = false)
    {
        try
        {
            if(is_string($pstring))
            {
                $regex = addslashes($pregex);
                if($pstrict === false) $regex = "~{$regex}~i";
                if($pstrict === true) $regex = "~{$regex}~";
                if(preg_match($regex,$pstring))
                {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } 
        catch (exception $e) 
        {
            return false;
        }
    }

    static function get_base64($pstring)
    {
        try
        {
            if(is_string($pstring))
            {
                if(function_exists("base64_encode"))
                {
                    return base64_encode($pstring);
                }
                throw new exception("This server cannot base64.");
            }
            throw new exception("This is not a string.");
        } 
        catch (exception $e) 
        {
            return false;
        }
    }

    static function concat($pfirststr,$psecondstr)
    {
        if(is_string($pfirststr) && is_string($psecondstr))
        {
            return $pfirststr.$psecondstr;
        } else {
            return false;
        }
    }

    static function get_utf8($pstring)
    {
        return str_replace("\xc2\x92","'",utf8_encode($pstring));
    }

    static function get_random($plenght = 1,$pignore_list = false)
    {
        try
        {
            if($pignore_list === false || is_string($pignore_list)) $pignore_list = array();
            $output = "";
            for($i = 0; $i < $plenght;$i++)
            {
                $random_type = rand(1,4);
                $next_char = '';
                if($random_type === 1)
                {
                    $next_char = self::chars[array_rand(self::chars)];
                    while(in_array($next_char,$pignore_list))
                    {
                        $next_char = self::chars[array_rand(self::chars)];
                    }
                }
                if($random_type === 2)
                {
                    $next_char = self::numbers[array_rand(self::numbers)];
                    while(in_array($next_char,$pignore_list))
                    {
                        $next_char = self::numbers[array_rand(self::numbers)];
                    }
                }
                if($random_type === 3)
                {
                    $next_char = self::specials[array_rand(self::specials)];
                    while(in_array($next_char,$pignore_list))
                    {
                        $next_char = self::specials[array_rand(self::specials)];
                    }
                }
                if($random_type === 4)
                {
                    $next_char = self::accents[array_rand(self::accents)];
                    while(in_array($next_char,$pignore_list))
                    {
                        $next_char = self::accents[array_rand(self::accents)];
                    }
                }
                $output .= $next_char;
                if(strlen($output) === $plenght) return self::get_utf8($output);
            }
            return self::get_utf8($output);
        }
        catch(exception $e)
        {
            return "";
        }

    }

    static function get_safe_random($plenght =1)
    {
        try
        {
            $output = "";
            for($i = 0; $i < $plenght;$i++){
                $random_type = rand(1,2);
                $next_char = '';
                if($random_type === 1)
                {
                    $next_char = self::chars[array_rand(self::chars)];
                }
                if($random_type === 2)
                {
                    $next_char = self::numbers[array_rand(self::numbers)];
                }
                $output .= $next_char;
                if(strlen($output) === $plenght) return self::get_utf8($output);
            }
            return self::get_utf8($output);
        }
        catch(Exception $e)
        {
            return "";
        }

    }

    static function get_hex($pstring,$pdelimiter = false)
    {
        try
        {
            $string = (string)$pstring;
            if(is_string($string))
            {
                $string = str_split($string);
                foreach($string as &$char)
                {
                    if($pdelimiter) 
                    {
                        $char = "\x".dechex(ord($char));
                    } else {
                        $char = dechex(ord($char));
                    }
                }
                return implode('',$string);
            }
            throw new exception("This is not a string.");
        }
        catch(exception $e)
        {
            return $pstring;
        }
    }

    static function decode_hex($pstring)
    {
        if(is_string($pstring))
        {
            $new_string = "";
            $hex_regex = new regex(sregex::hex);
            if($hexes = $hex_regex->get_matches($pstring))
            {
                foreach($hexes as $hex)
                {
                    foreach(ascii as $ascii_char)
                    {
                        if($ascii_char["hex"] == strtoupper($hex) || $ascii_char["hex"] === "\x".strtoupper($hex))
                        {
                            $new_string .= $ascii_char["char"];
                            break;
                        }
                    }
                }
                return $new_string;
            }
        }
        return $pstring;
    }

    static function encode_url($pstring)
    {
        if(is_string($pstring))
        {
            $new_string = "";
            foreach(str_split($pstring) as $char)
            {
                $new_char =  "";
                foreach(ascii as $ascii_char)
                {
                    if($char === $ascii_char["char"])
                    {
                        $new_char = $ascii_char["url"];
                        break;
                    }
                }
                if($new_char != "") $new_string .= $new_char;
                if($new_char === "") $new_string .= $char;
            }
            return $new_string;
        }
        return $pstring;
    }

    static function encode_double_url($pstring)
    {
        if(is_string($pstring))
        {
            $new_string = "";
            foreach(str_split($pstring) as $char)
            {
                $new_char =  "";
                foreach(ascii as $ascii_char)
                {
                    if($char === $ascii_char["char"])
                    {
                        $new_char = $ascii_char["double_url"];
                        break;
                    }
                }
                if($new_char != "") $new_string .= $new_char;
                if($new_char === "") $new_string .= $char;
            }
            return $new_string;
        }
        return $pstring;
    }

    static function decode_url($pstring)
    {
        return urldecode($pstring);
    }

    static function encode_charcode($pstring)
    {
        if(is_string($pstring))
        {
            $new_string = "";
            foreach(str_split($pstring) as $char)
            {
                $new_char =  "";
                foreach(ascii as $ascii_char)
                {
                    if($char === $ascii_char["char"])
                    {
                        $new_char = $ascii_char["char_code"];
                        break;
                    }
                }
                if($new_char != "") $new_string .= $new_char;
                if($new_char === "") $new_string .= $char;
            }
            return $new_string;
        }
        return $pstring;
    }

    static function decode_charcode($pstring)
    {
        if(is_string($pstring))
        {
            $new_string = "";
            foreach(str_split($pstring) as $char)
            {
                $new_char =  "";
                foreach(ascii as $ascii_char)
                {
                    if($char === $ascii_char["char_code"])
                    {
                        $new_char = $ascii_char["char_code"];
                        break;
                    }
                }
                if($new_char != "") $new_string .= $new_char;
                if($new_char === "") $new_string .= $char;
            }
            return $new_string;
        }
        return $pstring;
    }

}