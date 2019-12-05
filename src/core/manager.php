<?php
namespace core;

class manager implements \Iterator , \ArrayAccess, \Countable
{

    protected $array = array();
    
    protected $position = 0;

    public function __construct($pcomponent)
    {
        if($pcomponent instanceof component)
        {
            $this->array[] = $pcomponent;
        } 
        elseif(is_array($pcomponent))
        {
            foreach($pcomponent as $component)
            {
                $this->array[] = $component;
            }
        }
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
        if(is_null($offset))
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
        return isset($this->array[$offset]) ? $this->array[$offset] : false;
    }

    public function count()
    {
        return count($this->array);
    }

}
