<?php
namespace core\common\legacy;
use core\common\exception;

/**
 * Static PHP Library for legacy functions.
 *
 * This can be accessed at anytime to replace any functions needed in case that it 'dn't be supported.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class php
{

    static function split($pspliter,$pstring)
    {
        return explode($pspliter,$pstring);
    }

    static function hash_pbkdf2($palgorithm, $ppassword, $psalt, $pcount, $pkey_length, $praw_output = false)
    {
        try
        {
            $algorithm = strtolower($palgorithm);
            if(!in_array($algorithm, hash_algos(), true))
            {
                return false;
            }
            if($pcount <= 0 || $pkey_length <= 0)
            {
                return false;
            }
            $hash_length = strlen(hash($algorithm, "", true));
            $block_count = ceil($pkey_length / $hash_length);
            $output = "";
            for($i = 1; $i <= $block_count; $i++) 
            {
                // $i encoded as 4 bytes, big endian.
                $last = $psalt . pack("N", $i);
                // first iteration
                $last = $xorsum = hash_hmac($algorithm, $last, $ppassword, true);
                // perform the other $count - 1 iterations
                for ($j = 1; $j < $pcount; $j++) 
                {
                    $xorsum ^= ($last = hash_hmac($algorithm, $last, $ppassword, true));
                }
                $output .= $xorsum;
            }
            if($praw_output)
            {
                return substr($output, 0, $pkey_length);
            } else {
                return bin2hex(substr($output, 0, $pkey_length));
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function scandir($pfolderpath)
    {
        try
        {
            if(is_dir($pfolderpath))
            {
                $files = array();
                $directory = opendir($pfolderpath);
                while (($file = readdir($directory)) !== false)
                {
                    $files[] = $file;
                }
                closedir($directory);
                return $files;
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function file_put_contents($pfilepath, $pcontent){
        try
        {
            if(is_file($pfilepath))
            {
                $fh = fopen($pfilepath, "w");
                if(!$fh)
                {
                    throw new Exception("Permission denied to read file at {$pfilepath}.");
                }
                fwrite($fh, $pcontent);
                fclose($fh);
                return true;
            } else {
                throw new Exception("No folder found at {$pfilepath}.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function stripos($haystack, $needle, $offset = 0)
    {
        return strpos(strtolower($haystack), strtolower($needle), $offset);
    }

    static function json_encode($var)
    {
        switch (gettype($var)) 
        {
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'integer':
            case 'double':
                return $var;
            case 'resource':
            case 'string':
                if(!isset($replace_pairs))
                {
                    $replace_pairs = array(
                        '\\' => '\u005C',
                        '"' => '\u0022',
                        "\x00" => '\u0000',
                        "\x01" => '\u0001',
                        "\x02" => '\u0002',
                        "\x03" => '\u0003',
                        "\x04" => '\u0004',
                        "\x05" => '\u0005',
                        "\x06" => '\u0006',
                        "\x07" => '\u0007',
                        "\x08" => '\u0008',
                        "\x09" => '\u0009',
                        "\x0a" => '\u000A',
                        "\x0b" => '\u000B',
                        "\x0c" => '\u000C',
                        "\x0d" => '\u000D',
                        "\x0e" => '\u000E',
                        "\x0f" => '\u000F',
                        "\x10" => '\u0010',
                        "\x11" => '\u0011',
                        "\x12" => '\u0012',
                        "\x13" => '\u0013',
                        "\x14" => '\u0014',
                        "\x15" => '\u0015',
                        "\x16" => '\u0016',
                        "\x17" => '\u0017',
                        "\x18" => '\u0018',
                        "\x19" => '\u0019',
                        "\x1a" => '\u001A',
                        "\x1b" => '\u001B',
                        "\x1c" => '\u001C',
                        "\x1d" => '\u001D',
                        "\x1e" => '\u001E',
                        "\x1f" => '\u001F',
                        "'" => '\u0027',
                        '<' => '\u003C',
                        '>' => '\u003E',
                        '&' => '\u0026',
                        '/' => '\u002F',
                        "\xe2\x80\xa8" => '\u2028',
                        "\xe2\x80\xa9" => '\u2029',
                    );
                }
                return '"'.strtr($var,$replace_pairs).'"';
            case 'array':
                if (empty($var) || array_keys($var) === range(0, sizeof($var) - 1)) 
                {
                    $output = array();
                    foreach ($var as $v) 
                    {
                        $output[] = json_encode($v);
                    }
                    return '[ '.implode(', ',$output).' ]';
                }
            case 'object':
                $output = array();
                foreach ($var as $k => $v) 
                {
                    $output[] = json_encode(strval($k)) . ':' . json_encode($v);
                }
                return '{' . implode(', ', $output) . '}';
            default:
                return 'null';
        }
    }

    static function json_decode($json, $assoc = false)
    {
        $match = '/".*?(?<!\\\\)"/';

        $string = preg_replace( $match, '', $json );
        $string = preg_replace( '/[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', '', $string );

        if ( $string != '' )
        {
            return null;
        }

        $s2m = array();
        $m2s = array();

        preg_match_all( $match, $json, $m );

        foreach ( $m[0] as $s )
        {
            $hash = '"' . md5( $s ) . '"';
            $s2m[$s] = $hash;
            $m2s[$hash] = str_replace( '$', '\$', $s );
        }

        $json = strtr( $json, $s2m );

        $a = ( $assoc ) ? '' : '( object ) ';

        $data = array(
            ':' => '=>',
            '[' => 'array(',
            '{' => "{$a}array(",
            ']' => ')',
            '}' => ')'
        );

        $json = strtr( $json, $data );

        $json = preg_replace( '~([\s\(,>])(-?)0~', '$1$2', $json );

        $json = strtr( $json, $m2s );

        $function = @create_function( '', "return {$json};" );
        $return = ( $function ) ? $function() : null;

        unset( $s2m );
        unset( $m2s );
        unset( $function );

        return $return;
    }

}
