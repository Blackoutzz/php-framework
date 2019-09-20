<?php
namespace core\backend\network\curl;

/**
 * data container class that is an iterable array that contains headers
 *
 * headers description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Email   blackoutzzshoot@gmail.com
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class headers implements \Iterator , \ArrayAccess, \Countable
{

    protected $position = 0;

    protected $array = array();

    public function __construct()
    {

    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->array[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    public function offsetSet($offset, $value)
    {
        if(is_string($value))
        {
            $header = new header($value);
            $this->array[$header->get_key()] = $header;
        } else if($value instanceof header) {
            $this->array[$value->get_key()] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    public function count()
    {
        return count($this->array);
    }

    public function __toArray()
    {
        $array = array();
        foreach(array_reverse(get_object_vars($this),true) as $name => $value)
        {
            $array[$name] = $value;
        }
        return $array;
    }

    public function get_http_headers()
    {
        $http_headers = array();
        foreach($this->array as $header)
        {
            $http_headers[$header->get_key()] = $header->__toString();
        }
        return $http_headers;
    }

}
