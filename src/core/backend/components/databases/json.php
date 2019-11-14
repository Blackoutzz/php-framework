<?php
namespace core\backend\components\databases;
use core\backend\components\database;
use core\backend\database\json\connection;
use core\backend\database\json\model;
use core\common\regex;
use core\program;

/**
 * Json Database Object
 *
 * This object will handle dealing with the database internally.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class json extends database
{

    public function __construct($pconfig = array())
    {
        if(isset($pconfig["database"]) && regex::is_slug($pconfig["database"]))
        {
            $this->name = $pconfig["database"];
            $this->connection = new connection($pconfig["database"]);
            $this->model = new model();
        }
    }

}
