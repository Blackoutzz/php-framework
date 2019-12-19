<?php
namespace core\common\components;

class stackable implements \Iterator , \ArrayAccess, \Countable
{

    protected $array;

    protected $position = 0;

    public function __construct($parray = array())
    {
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
