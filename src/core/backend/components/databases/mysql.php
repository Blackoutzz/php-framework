<?php
namespace core\backend\components\databases;
use core\backend\components\database;
use core\backend\database\mysql\connection;
use core\backend\database\mysql\model;

/**
 * Mysql Database Object
 *
 * This object will handle dealing with the database internally.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class mysql extends database
{

    public function __construct($pconfig = array())
    {
        if(isset($pconfig["host"]) && isset($pconfig["port"]) && isset($pconfig["username"]) && isset($pconfig["password"]) && isset($pconfig["db"]))
        {
            $this->connection = new connection($pconfig["host"],$pconfig["port"],$pconfig["username"],$pconfig["password"],$pconfig["db"]);
            $this->model = new model($this->connection);
        }
    }

}
