<?php
namespace core\common\components;

class stackable extends exportable implements \Iterator , \ArrayAccess, \Countable
{

    protected $array;

    public function __construct($parray = array())
    {
        if(is_array($parray))
            $this->array = $parray;
        else
            $this->array = array();
    }

    public function rewind()
    {
        return reset($this->array);
    }

    public function current()
    {
        return current($this->array);
    }

    public function key()
    {
        return key($this->array);
    }

    public function next()
    {
        return next($this->array);
    }

    public function valid()
    {
        return key($this->array) !== null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
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
        if(isset($this->array[$offset])) 
            return $this->array[$offset];
        else
            return false;
    }

    public function count()
    {
        return count($this->array);
    }

    public function get_size()
    {
        return $this->count();
    }

}
