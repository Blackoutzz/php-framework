<?php
namespace core\backend\network\curl;

/**
 * curl posts.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class posts implements \Iterator , \ArrayAccess, \Countable
{

    protected $position = 0;

    protected $array = array();

    public function __construct()
    {

    }

    public function __toString()
    {
        $postfields = "";
        foreach($this->array as $post)
        {
            $postfields .= '&'.$post->__toString();
        }
        return ltrim($postfields,"&");
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
            $posts = explode('&',$value);
            foreach($posts as $post)
            {
                $post = new post($post);
                $this->array[$post->get_key()] = $post;
            }
        } else if($value instanceof post) {
            $posts = explode('&',$value->__toString());
            if(count($posts) >= 2)
            {
                foreach($posts as $post)
                {
                    $post = new post($post);
                    $this->array[$post->get_key()] = $post;
                }
            } else {
                $this->array[$value->get_key()] = $value;
            }

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

    public function contains_post()
    {
        foreach($this->array as $post)
        {
            if($post->get_type() === post_type::post) return true;
        }
        return false;
    }

    public function contains_form()
    {
        foreach($this->array as $post)
        {
            if($post->get_type() === post_type::form) return true;
        }
        return false;
    }

    public function contains_file_upload()
    {
        foreach($this->array as $post)
        {
            if($post->get_type() === post_type::file) return true;
        }
        return false;
    }

    public function add_form($pdata)
    {
        $post = new post($pdata,post_type::form);
        $this->array[$post->get_key()] = $post;
    }

    public function add_post($pdata)
    {
        $post = new post($pdata,post_type::post);
        $this->array[$post->get_key()] = $post;
    }

    public function add_file_upload($pdata)
    {
        $post = new post($pdata,post_type::file);
        $this->array[$post->get_key()] = $post;
    }

    public function get_postfields()
    {
        if($this->contains_file_upload() || $this->contains_form())
        {
            $postfields = array();
            foreach($this->array as $post)
            {
                $postfields[$post->get_key()] = $post->get_value();
            }
            return $postfields;
        }
        else
        {
            $postfields = "";
            foreach($this->array as $post)
            {
                $postfields .= '&'.$post->__toString();
            }
            return ltrim($postfields,"&");
        }

    }

}
