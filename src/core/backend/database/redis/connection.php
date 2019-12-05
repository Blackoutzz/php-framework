<?php
namespace core\backend\database\redis;
use core\backend\database\connection as database_connection;
use core\common\exception;
use \redis as redis_client;

/**
 * Redis Connection
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class connection extends database_connection
{

	protected $host;

	protected $port;
  
	protected $handler;

	public function __construct($phost,$pport)
	{
		$this->host = $phost;
		$this->port = $pport;
		$this->handler= new redis_client();
	}

	public function get_handler()
	{
		return $this->handler;
	}

	public function is_connected()
	{
		return $this->handler->ping();
	}

	public function connect()
	{
		try
		{
			if (!$this->handler->connect($this->host,$this->port))
			{
				throw new exception("Redis connection exception");
			}
			return true;
		}
		catch (exception $e)
		{
			return false;
		}
	}

	public function disconnect()
	{
		return $this->handler->close();
	}

	public function __destruct()
	{
		if($this->is_connected())
		{
			$this->disconnect();
		}
	}

	public function get_keys($ppattern)
    {
        try
        {
            if($this->is_connected())
            {
                return $this->handler->keys($ppattern);
			}
			throw new exception("Redis isn't connected or available");
        }
        catch(exception $e)
        {
            return NULL;
        }
    }

    public function get_value($pkey)
    {
        try
        {
            if ($this->is_connected())
            {
                return $this->handler->get($pkey);
			}
			throw new exception("Redis isn't connected or available");
        }
        catch(exception $e)
        {
            return NULL;
        }
    }

    public function set_key($pkey,$pvalue)
    {
        try
        {
            if ($this->is_connected())
            {
                return $this->handler->set($pkey,$pvalue);
			}
			throw new exception("Redis isn't connected or available");
        }
        catch(exception $e)
        {
            return NULL;
        }
    }

    public function get_set($pkey,$pvalue)
    {
        try
        {
            if ($this->is_connected())
            {
                return $this->handler->getSet($pkey,$pvalue);
			}
			throw new exception("Redis isn't connected or available");
        }
        catch(exception $e)
        {
            return NULL;
        }
    }

    public function get_multiple_values($pkey_array)
    {
        /*
        Get the values of all the specified keys.
        If one or more keys don't exist, the array will contain FALSE at the position of the key.
        */
        try
        {
            if ($this->is_connected())
            {
                return $this->handler->mGet($pkey_array);
			}
			throw new exception("Redis isn't connected or available");
        }
        catch(exception $e)
        {
            return NULL;
        }
    }

    /*
        Description: Verify if the specified key exists.
        Return value
    */

    public function key_exist($pkey)
    {
        try
        {
			if ($this->is_connected())
			{
				if (\is_string($pkey))
				{
					return $this->handler->exists($pkey);
				}
				else
				{
					throw new exception("key_exist takes a string as a parameter");
				}
			}
			throw new exception("Redis isn't connected or available");
        }
        catch(exception $e)
        {
        return NULL;
        }
    }

    /*
        Description: Remove specified keys.

        Parameters
        An array of keys, or an undefined number of parameters, each a key: key1 key2 key3 ... keyN

        Note: If you are connecting to Redis server >= 4.0.0 you can remove a key with the unlink method in the exact same way you would use del.
        The Redis unlink command is non-blocking and will perform the actual deletion asynchronously.

        Return value
        Long Number of keys deleted.
	*/
	
    public function delete($pkeys)
    {
        try
        {
            if ($this->is_connected())
            {
                return $this->handler->del($pkeys);
			}
			throw new exception("Redis isn't connected or available");
        }
        catch(exception $e)
        {
            return NULL;
        }
    }

}
