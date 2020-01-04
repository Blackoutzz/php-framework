<?php
namespace framework;
use core\programs\mvc;

/**
 * Main
 *
 * This is where everything start for the microservice
 *
 * @version 1.0
 * @author Mickael Nadeau
 **/

class main extends mvc
{

    public function __construct($pargv = array())
    {
        self::$path = "./";
        parent::__construct($pargv);
    }

}
