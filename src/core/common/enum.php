<?php
namespace app\core\common;

/**
 * enum
 *
 * Similar to c/c++ implementation using PHP
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class enum implements \Iterator , \ArrayAccess, \Countable
{

    protected $array = array();

    protected $position = 0;

    public function __construct($parray = array())
    {
        if(is_array($parray))
            $this->array = $parray;
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

    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetUnset($offset)
    {

    }

    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    public function count()
    {
        return count($this->array);
    }

}
