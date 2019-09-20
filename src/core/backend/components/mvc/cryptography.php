<?php
namespace core\backend\components\mvc;

define('default_crypto_salt',"000000000000000000000000000000000000000000");
define('default_crypto_algo',"sha1");

/**
 * Cryptography
 * 
 * Encryption app based
 * 
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class cryptography
{

    protected $salt;

    protected $algo;

    public function __construct($pconfig = array())
    {
        if(is_array($pconfig))
        {
            if(isset($pconfig["salt"])) $this->salt = $pconfig["salt"];
            else $this->salt = default_crypto_salt;
            if(isset($pconfig["algo"])) $this->algo = $pconfig["algo"];
            else $this->algo = default_crypto_algo;
        } else {
            $this->salt = default_crypto_salt;
            $this->algo = default_crypto_algo;
        }
    }

    public function hash($pdata)
    {
        return hash_hmac($this->algo,$pdata,$this->salt);
    }

}