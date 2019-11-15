<?php
namespace core\backend\database\json;
use core\backend\database\connection as database_connection;
use core\backend\components\filesystem\folder;
use core\backend\database\json\dataset_array;
use core\program;

class connection extends database_connection
{

    protected $database;

    public function __construct($pdatabase)
    {
        $this->handler = new folder(program::$path."db/".$this->database);
    }

    public function connect()
    {
        return $this->handler->exist();   
    }
    
    public function get_table($ptable)
    {
        $datasets = new dataset_array();
        $table_folder = new folder(program::$path."db/{$this->database}/{$ptable}/",false);
        if($table_folder->exist())
        {
            foreach($table_folder->get_files_by_extension("json") as $dataset)
            {
                $datasets[] = json_decode($dataset->get_contents());
            }
        }
        return $datasets;
    }

    public function create_table($ptable)
    {

    } 

}