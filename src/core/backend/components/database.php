<?php
namespace core\backend\components;

define("NULL",0);

/**
 * Database 
 *
 * @version 1.0
 * @author  mick@blackoutzz.me
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

abstract class database
{

	protected $name;

    protected $connection;

	protected $model;
	
	public function __construct($pconfig = array())
	{
		
	}

	protected function get_connection()
	{
		return $this->connection;
	}

	public function has_connection()
	{
		return !$this->connection === NULL || !\count($this->connection) === 0 ;
	}

	protected function set_connection($pconnection)
	{
		if(\is_array($pconnection))
		{
			$this->connection = $pconnection;
			return \count($pconnection);
		} else {
			$this->connection = $pconnection;
			return 1;
		}
	}

	public function get_model($pmodel = "core")
	{
		return $this->model;
	}
	
	public function get_name()
	{
		return $this->name;
	}

}
