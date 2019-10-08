<?php
namespace core\backend\components\mvc\controllers;
use core\backend\components\mvc\controller;
use core\backend\mvc\ajax\response;
use core\backend\mvc\ajax\response_code;
use core\common\conversions\json;
use core\program;

/**
 * Ajax API.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

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
                $response = new response(response_code::successful,$result,$body);
                echo $response;
                return true;
            } else {
                echo json_encode(array("code"=>404,"ret"=>false,"body"=>"Invalid Ajax Action"));
            }
        } else {
            echo json_encode(array("code"=>403,"ret"=>false,"body"=>"Access Denied"));
        }
        return false;
    }

    protected function is_login()
    {
        $is_login = parent::is_login();
        if(isset($_REQUEST["user-token"]))
        {
            return ($is_login && ($_SESSION["user"]["token"] === $_REQUEST["user-token"]));
        }
        return false;
    }

}
