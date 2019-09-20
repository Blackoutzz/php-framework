<?php
namespace core\backend\network\curl;

/**
 * post short summary.
 *
 * post description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class post
{

    protected $key;

    protected $value;

    protected $type;

    protected $mime_type;

    public function __construct($pheader,$ptype = curl_post_type::post)
    {
        $this->type = $ptype;
        if(is_string($pheader))
        {
            if(preg_match("~^([^=:]*)[=:](.*)$~m",$pheader,$array))
            {
                $this->key = $array[1];
                $this->value = $array[2];
                if(preg_match("~^@?[\.\/\\\\A-z_-0-9].*$~mi",$this->value) || preg_match("~^.*(?:file|upload|fileupload|file_upload|_upload|files|\[file\]|\[files\])$~mi",$this->key))
                {
                    if(!preg_match("~^http.*$~mi",$this->value)
                    && !preg_match("~\/?\.\.\/\.\.\/.+~mi",urldecode($this->value))
                    && !preg_match("~^[0-9]+$~mi",urldecode($this->value))
                    && !preg_match("~^[A-z]+$~mi",urldecode($this->value))
                    && !preg_match("~^\/wp-content\/.+$~mi",urldecode($this->value))
                    && preg_match("~^@?\.?\/?(?:[^\s\t\r]|\\\\[\s\t\r])+$~mi",$this->value))
                    {
                        $this->type = post_type::file;
                        if(preg_match("~^([^;]+);[\s\t\r]*type=([^\s\t\r]*)[\s\t\r]*$~im",$this->value,$mime_type)){
                            $this->value = $mime_type[1];
                            $this->mime_type = $mime_type[2];
                        }
                    }
                }
            } else
            {
                $this->key = "";
                $this->value = $pheader;
            }
        }
        elseif(is_array($pheader))
        {
            foreach($pheader as $key => $value)
            {
                $this->key = $key;
                $this->value = $value;
            }
        }
    }

    public function __toString()
    {
        if($this->key) return $this->key."=".$this->value;
        else return $this->value;

    }

    public function is_valid()
    {
        return ($this->key != "");
    }

    public function get_key()
    {
        return $this->key;
    }

    public function get_value()
    {
        if($this->type != post_type::file)
            return $this->value;
        else
        {
            if(isset($this->mime_type) && $this->mime_type != "")
                return curl_file_create($this->value,$this->mime_type,$this->key);
            else
                return curl_file_create($this->value,"",$this->key);
        }

    }

    public function get_type()
    {
        return $this->type;
    }

    public function __toArray()
    {
        return array($this->key => $this->value);
    }

}
