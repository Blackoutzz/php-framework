<?php
namespace core\backend\components\mvc\controllers;
use core\backend\components\mvc\controller;

/**
 * Page Controller.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class page extends controller
{

    public function initialize()
    {
        if($this->has_access() && $this->has_view())
        {
            $this->prepare_view();
            $view = $this->create_view();
            $view->load_layout();
            return true;
        } else {
            return false;
        }
    }

}
