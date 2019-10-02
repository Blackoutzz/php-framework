<?php
namespace core\backend\system;

abstract class program
{

    protected $name;

    protected $path;

    public function __construct($pname , $ppath = "")
    {
        $this->path = $ppath;
        $this->name = $pname;
        if($this->path != "" && is_string($this->path))
        {
            if(!preg_match("~{$this->path}~i",$_ENV["PATH"]))
                 putenv("PATH=".$_ENV["PATH"].$this->path);
        }
    }

    protected function on_windows()
    {
        //Overrite in your class to run this when on windows
    }

    protected function on_unix()
    {
        //Overrite in your class to run this when on unix / linux
    }

    protected function on_macos()
    {
        //Overrite in your class to run this when on mac osx
    }

}