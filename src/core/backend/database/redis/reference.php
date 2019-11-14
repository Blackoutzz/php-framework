<?php
namespace core\backend\database\redis;
use core\backend\database\reference as database_reference;
use core\backend\components\databases\redis as redis_database;
use core\program;

/**
 * Redis Reference
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class reference extends database_reference
{
    
    protected function has_database()
    {
        if(count(program::$databases) >= 1)
        {
            foreach(program::$databases as $database)
            {
                if($database instanceof redis_database)
                {
                    return true;
                }
            }
        }
        return false;
    }
    
}
