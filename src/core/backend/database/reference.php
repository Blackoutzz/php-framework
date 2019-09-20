<?php
namespace core\backend\database;
use core\backend\database\model;
use core\backend\components\database;
use core\program;

class reference 
{

    protected function get_database_model()
    {
        if(program::$database instanceof database)
        {
            $model = program::$database->get_model();
            if($model instanceof model)
            {
                return $model;
            }
        }
        return false;
    }

    protected function get_database_connection()
    {
        if(program::$database instanceof database)
        {
            $connection = program::$database->get_connection();
            if($connection instanceof connection)
            {
                return $connection;
            }
        }
        return false;
    }

    protected function has_database()
    {
        return (program::$database instanceof database);
    }

    protected function is_database_connected()
    {
        if(program::$database instanceof database)
        {
            return program::$database->is_connected();
        }
        return false;
    }

}
