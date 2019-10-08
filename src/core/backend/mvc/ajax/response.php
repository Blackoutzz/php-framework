<?php
namespace core\backend\mvc\ajax;

/**
 * Ajax Response.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class response
{

    protected $code;

    protected $result;

    protected $body;

    public function __construct($pcode = ajax_response_code::successful,$presult=false,$pbody="")
    {
        $this->code = $pcode;
        $this->result = $presult;
        $this->body = $pbody;
    }

    public function __toArray()
    {
        return array("code"=>$this->code,"ret"=>$this->result,"body"=>$this->body);
    }

    public function __toJson()
    {
        return json_encode($this->__toArray());
    }

    public function __toString()
    {
        return $this->__toJson();
    }

}