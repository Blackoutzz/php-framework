<?php
namespace core\backend\components\databases;
use core\backend\components\database;
use core\backend\database\redis\connection;
use core\backend\database\redis\model;

/**
 * Redis Database
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class redis extends database
{

    public function __construct($pconfig)
    {
        if(isset($pconfig['host']) && isset($pconfig['port']))
        {
            $this->name = $pconfig['host'].":".$pconfig['port'];
            $this->connection = new connection($pconfig['host'],$pconfig['port']);
            $this->connection->connect();
            $this->model = new model();
        }
    }

}
