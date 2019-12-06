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
		$model_name = trim(strtolower($pmodel));
		$name = $this->get_name();
		if($model_name === "core") return $this->model;
		
		if(class_exists("core\\backend\\database\\{$name}\\models\\{$model_name}"))
		{
			$namespace = "core\\backend\\database\\{$name}\\models\\{$model_name}";
			return new $namespace($this->connection);
		} else {
			return $this->model;
		}
	}
	
	public function get_name()
	{
		$classname = explode('\\', get_class($this));
		return array_pop($classname);
	}

}
