<?php
namespace core\common;
use core\program;
use core\backend\database\mysql\model;
use core\backend\components\log;
use core\common\str;
use core\common\time\sdate;
use core\common\time\date;
use core\backend\components\filesystem\file;

/**
 * App - Basic Exception Handler
 *
 * This exception handler is making sure to log everything related to any malfunction inside the app.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class exception extends \Exception
{

    protected $log;

    protected $date;

    static $exceptions = array();

    public function __construct($message = "", $code = 0, \Throwable $previous = NULL)
    {
        parent::__construct(str::sanitize($message),$code,$previous);
        self::$exceptions[] = $this;
        $this->date = time();
        $this->log = new log("exceptions");
        $this->save();
    }

    protected function save()
    {
        $id=0;
        if(isset(program::$user))
        {
            $id = program::$user->get_id();
        }
        $exception_log = array(
            "date"=>$this->date,
            "user"=>$id,
            "msg"=>$this->message,
            "file"=>$this->file,
            "code"=>$this->code,
            "line"=>$this->line
        );
        $this->log->save($exception_log);
    }

    public function get_code()
    {
        return $this->getCode();
    }

    public function get_message()
    {
        return $this->getMessage();
    }

    public function get_file()
    {
        return $this->getFile();
    }

    public function get_line()
    {
        return $this->getLine;
    }

    public function get_date()
    {
        return new date($this->date);
    }

    static function get_exceptions()
    {
        return self::$exceptions;
    }

}

class exception_log
{

    protected $date;

    protected $user;

    protected $message;

    protected $file;

    protected $code;

    protected $line;

    public function __construct($pdata)
    {
        $this->date = $pdata["date"];
        $this->user = model::get_user_by_id($pdata["user"]);
        $this->file = $pdata["file"];
        $this->code = $pdata["code"];
        $this->line = $pdata["line"];
        $this->message = $pdata["msg"];
    }

    public function get_user()
    {
        return $this->user;
    }

    public function get_date()
    {
        return new date($this->date,sdate::today);
    }

    public function get_message()
    {
        return $this->message;
    }

    public function get_file()
    {
        return new file($this->file);
    }

    public function get_code()
    {
        return $this->code;
    }

    public function get_line()
    {
        return $this->line;
    }

}