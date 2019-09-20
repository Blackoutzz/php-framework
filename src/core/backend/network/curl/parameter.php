<?php
namespace core\backend\network\curl;

/**
 * A data container class that represents a parameter for a curl command
 *
 * parameter description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Email   blackoutzzshoot@gmail.com
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class parameter
{

    protected $id;

    protected $name;

    protected $shortcut;

    protected $use_parameter;

    public function __construct($pid = 0,$pname="",$pshortcut = "",$phas_param = false)
    {
        $this->id = $pid;
        $this->name = $pname;
        $this->use_parameter = ($phas_param === true);
        $this->shortcut = $pshortcut;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_shortcut()
    {
        return $this->shortcut;
    }

    public function has_parameter()
    {
        return $this->use_parameter;
    }
}