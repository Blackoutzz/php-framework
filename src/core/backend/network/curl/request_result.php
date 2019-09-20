<?php
namespace core\backend\network\curl;
use core\backend\network\http\status_code;
use core\common\components\regex;

/**
 * request_result short summary.
 *
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Email   blackoutzzshoot@gmail.com
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class request_result
{

    protected $code = 0;

    protected $headers = "";

    protected $body = "";

    protected $error = "";

    public function __construct($pcode,$pheaders,$pbody,$perror)
    {
        $this->headers = $pheaders;
        $this->body = $pbody;
        $this->code = $pcode;
        $this->error = $perror;
    }

    public function __toString()
    {
        return $this->body;
    }

    public function get_headers()
    {
        return $this->headers;
    }

    public function get_code()
    {
        return $this->code;
    }

    public function get_error()
    {
        return $this->error;
    }

    public function get_body()
    {
        return $this->body;
    }

    public function is_received()
    {
        if($this->code != 0 && $this->body != false) return true;
        return false;
    }

    public function is_successful()
    {
        return status_code::is_successful($this->code);
    }

    public function is_redirected()
    {
        return status_code::is_redirected($this->code);
    }

    public function is_failed()
    {
        return status_code::is_failed($this->code);
    }

    public function is_crashed()
    {
        return status_code::is_crashed($this->code);
    }

    public function is_blocked()
    {
        return status_code::is_blocked($this->code);
    }

    public function is_cloudproxy_blocked()
    {
        return status_code::is_cloudproxy_blocked($this->code);
    }

    public function get_cloudproxy_block_id()
    {
        try{
            if($this->body === "" || !$this->code || $this->code == 0) throw new exception("NULL Request result");
            if($this->code === status_code::internal_server_error) return "NONE";
            if($this->code === status_code::forbidden) {
                $block = new regex("X-Sucuri-Block: +([A-z0-9]+) *");
                if($block->match($this->headers)){
                    return $block->get_match($this->headers)[1];
                }
            }
            return "NONE";
        }
        catch (Exception $e) {
            return "NONE";
        }
    }

    public function is_cloudflare_blocked()
    {
        return status_code::is_cloudflare_blocked($this->code);
    }

}