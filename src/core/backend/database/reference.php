<?php
namespace core\backend\database;
use core\backend\database\model;
use core\backend\components\database;
use core\program;

class reference
{
    
    protected function has_database()
    {
        return (count(program::$databases) >= 1);
    }

}
