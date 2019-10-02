<?php
namespace core\backend\components\mvc\controllers;
use core\backend\components\mvc\controller;
use core\frontend\components\mvc\controller_view;
use core\common\conversions\json;
use core\program;

/**
 * Ajax API.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class ajax_response_code
{
    const successful = 200;
    const access_denied = 403;
    const invalid_action = 404;
    const unexpected_error = 500;
}

class ajax_response
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

}

class ajax extends controller
{

    public function initialize()
    {
        header("Content-Type: text/json");
        if($this->has_access())
        {
            if($this->has_view())
            {
                $result = $this->prepare_view();
                $body = program::pull();
                $response = new ajax_response(ajax_response_code::successful,$result,$body);
                echo $response->__toJson();
                return true;
            } else {
                echo json_encode(array("code"=>404,"ret"=>false,"body"=>"Invalid Ajax Action"));
            }
        } else {
            echo json_encode(array("code"=>403,"ret"=>false,"body"=>"Access Denied"));
        }
        return false;
    }

    protected function create_view()
    {
        return new controller_view($this->reference,$this->cache,$this->view_data);
    }

    protected function is_login()
    {
        $is_login = parent::is_login();
        if(isset($_REQUEST["user-token"]))
        {
            if($is_login && ($_SESSION["user"]["token"] === $_REQUEST["user-token"])) return true;
        }
        return false;
    }

}