<?php
namespace core\backend\components\filesystem;
use core\backend\filesystem\file as static_file;
/**
 * Object File.
 *
 * This class contains everything included with the static file library.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class file
{

    protected $filepath;

    public function __construct($pfilepath,$pauto_create = true)
    {
        $this->filepath = $pfilepath;
        if(!$this->exist() && $pauto_create === true)
        {
            $this->create();
        }
    }

    public function __toString()
    {
        return static_file::get_path($this->filepath);
    }

    public function get_path()
    {
        return static_file::get_path($this->filepath);
    }

    public function exist()
    {
        return static_file::exist($this->filepath);
    }

    public function get_name()
    {
        return static_file::get_name($this->filepath);
    }

    public function get_folder()
    {
        return static_file::get_folder($this->filepath);
    }

    public function get_size()
    {
        return static_file::get_size($this->filepath);
    }

    public function get_owner()
    {
        return static_file::get_owner($this->filepath);
    }

    public function is_executable()
    {
        return static_file::is_executable($this->filepath);
    }

    public function is_writable()
    {
        return static_file::is_writable($this->filepath);
    }

    public function is_readable()
    {
        return static_file::is_readable($this->filepath);
    }

    public function is_hidden()
    {
        return static_file::is_hidden($this->filepath);
    }

    public function get_permissions()
    {
        return static_file::get_permissions($this->filepath);
    }

    public function set_permissions($ppermissions = 0644)
    {
        return static_file::set_permissions($this->filepath,$ppermissions);
    }

    public function set_permission_mode($pmode = "default")
    {
        return static_file::set_permission_mode($this->filepath,$pmode);
    }

    public function get_contents()
    {
        return static_file::get_contents($this->filepath);
    }

    public function add_contents($pcontent = '')
    {
        return static_file::add_contents($this->filepath,$pcontent);
    }

    public function set_contents($pcontent = '')
    {
        return static_file::set_contents($this->filepath,$pcontent);
    }

    public function create()
    {
        return static_file::create($this->filepath);
    }

    public function remove()
    {
        return static_file::remove($this->filepath);
    }

    public function copy($pnew_filepath)
    {
        return static_file::copy($this->filepath,$pnew_filepath);
    }

    public function move($pnew_filepath)
    {
        return static_file::move($this->filepath,$pnew_filepath);
    }

    public function get_lines()
    {
        return static_file::get_lines($this->filepath);
    }

    public function get_line_by_id($pindex = 1)
    {
        return static_file::get_line_by_id($this->filepath,$pindex);
    }

    public function get_extension()
    {
        return static_file::get_extension($this->filepath);
    }

    public function get_extension_icon($psize = 1)
    {
        return static_file::get_extension_icon($this->filepath,$psize);
    }

    public function get_extension_type()
    {
        return static_file::get_extension_type($this->filepath);
    }

    public function get_file_type()
    {
        return static_file::get_file_type($this->filepath);
    }

    public function extract($pfolderpath = './')
    {
        return static_file::extract($this->filepath,$pfolderpath);
    }

    public function has_pattern($pregex)
    {
        return static_file::has_pattern($this->filepath,$pregex);
    }

    public function import()
    {
        return static_file::import($this->filepath);
    }

    public function unzip($pdestination_folder = false)
    {
        if(!$pdestination_folder) $pdestination_folder = static_file::get_folder($this->filepath);
        return static_file::unzip($this->filepath,$pdestination_folder);
    }

    public function rename($pnew_name)
    {
        return static_file::rename($this->filepath,$pnew_name);
    }

}
