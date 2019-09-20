<?php
namespace core\backend\components\mvc\controllers;
use core\backend\components\mvc\controller;
use core\frontend\components\controller_view;
use core\common\conversions\json;
use core\program;

/**
 * Ajax Controller
 *
 * used by javascript
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class secured_ajax extends controller
{

    public function initialize()
    {
        header("Content-Type: text/json");
        if($this->has_access() && $this->is_login())
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
        return new controller_view($this->reference,$this->view_data);
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