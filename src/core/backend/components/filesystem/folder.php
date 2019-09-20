<?php
namespace core\backend\components\filesystem;
use core\backend\filesystem\folder as static_folder;
/**
 * Object Folder.
 *
 * This class contains everything included with the static folder library.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class folder 
{

    protected $folderpath;

    public function __construct($pfolderpath = "./",$pauto_create = true)
    {
        $this->folderpath = static_folder::get_path($pfolderpath);
        if(!$this->exist() && $pauto_create === true)
        {
            $this->create();
        }
    }

    public function __destruct()
    {
        //
    }

    public function __toString()
    {
        return static_folder::get_path($this->folderpath);
    }

    public function get_path()
    {
        return static_folder::get_path($this->folderpath);
    }

    public function get_name()
    {
        return static_folder::get_name($this->folderpath);
    }

    public function exist()
    {
        return static_folder::exist($this->folderpath);
    }

    public function get_permissions()
    {
        return static_folder::get_permissions($this->folderpath);
    }

    public function get_parent_folder()
    {
        return static_folder::get_parent_folder($this->folderpath);
    }

    public function get_owner()
    {
        return static_folder::get_owner($this->folderpath);
    }

    public function get_directory($precursive = false)
    {
        return static_folder::get_directory($this->folderpath,$precursive);
    }

    public function get_directory_by_pattern($pregex = "",$precursive = false)
    {
        return static_folder::get_directory_by_pattern($this->folderpath,$pregex,$precursive);
    }

    public function get_directory_by_extension($pextension = "",$precursive = false)
    {
        return static_folder::get_directory_by_extension($this->folderpath,$pextension,$precursive);
    }

    public function get_files($precursive = false)
    {
        return static_folder::get_files($this->folderpath,$precursive);
    }

    public function get_file($pfilename)
    {
        return static_folder::get_file($this->folderpath,$pfilename);
    }

    public function get_files_by_pattern($precursive = false)
    {
        return static_folder::get_files_by_pattern($this->folderpath,$precursive);
    }

    public function get_files_by_extension($pextention = "php",$precursive = false)
    {
        return static_folder::get_files_by_extension($this->folderpath,$pextention,$precursive);
    }

    public function get_folders($precursive = false)
    {
        return static_folder::get_folders($this->folderpath,$precursive);
    }

    public function get_folder($pfoldername)
    {
        return static_folder::get_folder($this->folderpath,$pfoldername);
    }

    public function harden($poption = "default")
    {
        return static_folder::harden($this->folderpath,$poption);
    }

    public function create($ppermissions = 0755)
    {
        return static_folder::create($this->folderpath,$ppermissions);
    }

    public function import($precursive = false)
    {
        return static_folder::import($this->folderpath,$precursive);
    }

    public function remove()
    {
        return static_folder::remove($this->folderpath);
    }

    public function rename($pnew_name)
    {
        return static_folder::rename($this->folderpath,$pnew_name);
    }

    public function has_folder($pfoldername)
    {
        return static_folder::has_folder($this->folderpath,$pfoldername);
    }

    public function is_hidden()
    {
        return static_folder::is_hidden($this->folderpath);
    }

    public function has_file($pfilename)
    {
        return static_folder::has_file($this->folderpath,$pfilename);
    }

    public function create_file($pfilename)
    {
        return static_folder::create_file($this->folderpath,$pfilename);
    }

    public function create_folder($pfoldername)
    {
        return static_folder::create_folder($this->folderpath,$pfoldername);
    }

}
