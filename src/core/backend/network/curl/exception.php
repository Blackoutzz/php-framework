<?php
namespace core\backend\network\curl;
use core\common\exception as app_exception;

/**
 * This acts as a data container for mick`s custom curl module`s exceptions
 *
 *
 * Module is simple, no need for a detailed description the summary is plenty
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Email   blackoutzzshoot@gmail.com
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class exception extends app_exception
{
    protected $code;

    protected $message;

    protected $options;

    public function __construct($pmsg = "",$pcode = 0,$poptions = array())
    {
        $this->code = $pcode;
        $this->message = $pmsg;
        $this->options = $poptions;
        if($this->code === 26) $this->message ="CURL Request Failed to create formpost data.";
    }

    public function get_code()
    {
        return $this->code;
    }

    public function get_message()
    {
        return $this->message;
    }

    public function get_options()
    {
        return $this->options;
    }

}