<?php
namespace core\backend\mvc\ajax;
use core\backend\database\dataset;

/**
 * Ajax Response.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class response extends dataset
{

    protected $value;

    public function __construct($pdata)
    {
        $this->value = $pdata;
    }

    public function __toString()
    {
        return $this->__toJson();
    }

}