<?php
namespace core\backend\components\mvc\controllers;
use core\backend\components\mvc\controller;
use core\frontend\components\mvc\controller_view;

/**
 * Page Controller.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class page extends controller
{

    public function initialize()
    {
        if($this->has_access() && $this->has_view())
        {
            $this->prepare_view();
            $controller_view = new controller_view($this->reference,$this->cache,$this->view_data);
            $controller_view->load_layout();
            return true;
        } else {
            return false;
        }
    }

}
