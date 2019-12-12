<?php
namespace framework;
use core\program;

/**
 * Main
 *
 * This is where everything start for the microservice
 *
 * @version 1.0
 * @author Mickael Nadeau
 **/

class main extends program
{

    public function __construct($pargv = array())
    {
        self::$path = "./";
        parent::__construct($pargv);
        $controller = self::$routing->get_controller_instance();
        $controller->initialize(); 
    }

}
