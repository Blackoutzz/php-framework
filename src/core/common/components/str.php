<?php
namespace core\common\components;

/**
 * str short summary.
 *
 * str description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class str {

    protected $string;

    public function __construct($pstring)
    {
        $this->string = (string) $pstring;
    }

    public function __toString()
    {
        return $this->string;
    }

    public function get_length()
    {
        return sstr::get_length($this->string);
    }

    public function remove($premove)
    {
        $this->string = sstr::remove($this->string,$premove);
        return $this->string;
    }

    public function replace($preplace,$preplaced = " ")
    {
        $this->string = sstr::replace($this->string,$preplace,$preplaced);
        return $this->string;
    }

    public function is_sanitized()
    {
        return sstr::is_sanitized($this->string);
    }

    public function sanitize()
    {
        return sstr::sanitize($this->string);
    }

    public function contains($pvalue,$pstrict = false)
    {
        return sstr::contains($this->string,$pvalue,$pstrict);
    }

    public function get_lines()
    {
        return sstr::get_lines($this->string);
    }

    public function get_line_by_id($pid)
    {
        return sstr::get_line_by_id($this->string,$pid);
    }

    public function get_compress()
    {
        return sstr::compress($this->string);
    }

    public function compress()
    {
        $this->string = sstr::compress($this->string);
        return $this->string;
    }

    public function get_uncompress()
    {
        return sstr::uncompress($this->string);
    }

    public function uncompress()
    {
        $this->string = sstr::uncompress($this->string);
        return $this->string;
    }

    public function output($pjson = false)
    {
        sstr::output($this->string,$pjson);
    }

    public function write($psanitize = true)
    {
        sstr::write($this->string,$psanitize);
    }

    public function writeline($psanitize = true)
    {
        sstr::writeline($this->string,$psanitize);
    }

    public function get_base64()
    {
        return sstr::get_base64($this->string);
    }

    public function get_contents()
    {
        return $this->string;
    }

    public function get_utf8()
    {
        return sstr::get_utf8($this->string);
    }

    public function get_bool()
    {
        if($this->string) return true;
        return false;
    }

    public function get_int()
    {
        return intval($this->string);
    }

    public function get_md5()
    {
        return md5($this->string);
    }

    public function get_hex($pdelimiter = false)
    {
        return sstr::get_hex($this->string,$pdelimiter);
    }

    public function convert_to_utf8()
    {
        $this->sstr::get_utf8($this->string);
    }

    public function convert_to_base64()
    {
        $this->string = sstr::get_base64($this->string);
    }

    public function convert_to_hex($pdelimter = false)
    {
        $this->string = sstr::get_hex($this->string,$pdelimter);
    }

}
