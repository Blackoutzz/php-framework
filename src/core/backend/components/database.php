<?php
namespace backend\components;

define("NULL",0);

/**
 *
 *
 * @Version 1.0
 * @Author  mick@blackoutzz.me
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class database
{

	protected $connection;

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

}
