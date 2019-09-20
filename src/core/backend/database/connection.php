<?php
namespace core\backend\database;

/**
 * Database Connection
 * 
 * Default database connection for database component
 * 
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class connection
{
    protected $host;

    protected $port;

    protected $handler;

    public function __construct($phost,$pport)
    {
		$this->host = $phost;
		$this->port = $pport;
    }

    public function get_handler()
    {
      	return $this->handler;
    }

    public function is_connected()
    {
      	return is_resource($this->handler);
    }

    public function disconnect()
    {
      	return false;
    }

    public function __destruct()
    {
        if($this->is_connected())
        {
            $this->disconnect();
        }
    }
}
