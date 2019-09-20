<?php
namespace core\common\components;
use core\common\regex as static_regex;

/**
 * Regex Object
 *
 * Incapsulate the regex static library
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class regex 
{

    protected $regex;

    protected $flags;

    public function __construct($pregex = "",$pflags = "im")
    {
        $this->flags = $pflags;
        $this->regex = $pregex;
    }

    public function __toString()
    {
        return "/{$this->regex}/{$this->flags}";
    }

    public function match($pinput)
    {
        return static_regex::is_matching($this->regex,$pinput,$this->flags);
    }

    public function is_matching($pinput)
    {
        return static_regex::is_matching($this->regex,$pinput,$this->flags);
    }

    public function get_match($pinput)
    {
        return static_regex::get_match($this->regex,$pinput,$this->flags);
    }

    public function get_matches($pinput)
    {
        return static_regex::get_matches($this->regex,$pinput,$this->flags);
    }

}
