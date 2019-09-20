<?php
namespace core\common\location;


/**
 * country short summary.
 *
 * country description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class country
{

    protected $id;

    protected $name;

    public function __construct($pid,$pname)
    {
        $this->id = $pid;
        $this->name = $pname;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_name()
    {
        return $this->name;
    }

}