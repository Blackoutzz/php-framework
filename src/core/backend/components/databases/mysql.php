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
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class mysql extends database
{

    protected $connection;

    protected $model;

    public function __construct($pconfig = array())
    {
        $this->connection = new connection($pconfig["host"],$pconfig["port"],$pconfig["username"],$pconfig["password"],$pconfig["db"]);
        $this->model = new model($this->connection);
    }

    public function get_model()
    {
        return $this->model;
    }

    public function get_connection()
    {
        return $this->connection;
    }

}
