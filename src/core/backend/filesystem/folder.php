<?php
namespace core\backend\filesystem;
use core\backend\filesystem\file as static_file;
use core\backend\components\filesystem\folder as object_folder;
use core\backend\components\filesystem\file;
use core\common\exception;

/**
 * Static Folder Library.
 *
 * This act as the backbone of the Object Folder Library and can be used directly.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class folder
{

    static function get_path($pfolderpath)
    {
        try
        {
            $folderpath = preg_replace("~[////\/]~",DIRECTORY_SEPARATOR,$pfolderpath);
            //Default folder check
            if($folderpath === ".".DIRECTORY_SEPARATOR || $folderpath === "..".DIRECTORY_SEPARATOR)
                return $folderpath;
            //Default folder rectification
            if($folderpath === "." || $folderpath === "..")
                return $folderpath.DIRECTORY_SEPARATOR;
            //Normal Path
            if(preg_match("~^(?:\.?[\\\\\/]|[\\\\\/]?)(.+)[\\\\\/]$~im",$folderpath,$new_folderpath))
            {
                if(preg_match("~^[A-Za-z]\:[\\\\].*$~im",$new_folderpath[1]))
                    return $new_folderpath[1].DIRECTORY_SEPARATOR;
                if(is_dir(".".DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR))
                    return ".".DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR;
                if(is_dir(DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR))
                    return DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR;
            }
            //Normal Path Without Folder /
            if(preg_match("~^(?:\.?[\\\\\/]|[\\\\\/]?)(.+)$~im",$folderpath,$new_folderpath))
            {
                if(is_dir($new_folderpath[1].DIRECTORY_SEPARATOR))
                    return $new_folderpath[1].DIRECTORY_SEPARATOR;
                if(is_dir(".".DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR))
                    return ".".DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR;
                if(is_dir(DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR))
                    return DIRECTORY_SEPARATOR.$new_folderpath[1].DIRECTORY_SEPARATOR;
            }
            throw new exception("No Folderpath filter matched '{$pfolderpath}'.");
        }
        catch (exception $e)
        {
            return $pfolderpath;
        }
    }

    static function get_name($pfolderpath)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                if($folderpath === "../") return "Parent Directory";
                if($folderpath === "./") return "Root Directory";
                if(preg_match('~^.*[\\\\\/]([^\\\\\/]+)[\\\\\/]$~im',$folderpath,$new_folderpath)){
                    return $new_folderpath[1];
                } else {
                    return $folderpath;
                }
            } else {
                throw new exception("No folder found at {$folderpath}.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function exist($pfolderpath)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                return true;
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function get_permissions($pfolderpath)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                return substr(sprintf('%o', fileperms($folderpath)),-4);
            } else {
                throw new exception("No folder found at {$folderpath}.");
            }
        }
        catch(exception $e)
        {
            return false;
        }

    }

    static function get_parent_folder($pfolderpath)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                if($folderpath === "./" || $folderpath === "/" || $folderpath === "." || $folderpath === "../" || $folderpath === "..")
                {
                    return "./";
                }
                if(preg_match('~^(.*[\\\\\/])[^\\\\\/]+[\\\\\/]$~im',$folderpath,$new_folderpath))
                {
                    return $new_folderpath[1];
                } else {
                    return $folderpath;
                }
            } else {
                throw new exception("No folder found at {$folderpath}.");
            }
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function get_owner($pfolderpath)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                if(function_exists("posix_getpwuid"))
                {
                    return posix_getpwuid(fileowner($folderpath))["name"];
                } else {
                    return fileowner($folderpath);
                }
            } else {
                throw new exception("No folder found at {$folderpath}");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_directory($pfolderpath = "./",$precursive = false)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $directory = array("folders"=>array(),"files"=>array(),"path"=>$folderpath);
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_file($new_filepath)) $directory["files"][$new_filepath] = new file($new_filepath,false);
                    if(is_dir($new_filepath) && !$precursive) $directory["folders"][$new_filepath] = new object_folder($new_filepath,false);
                    if(is_dir($new_filepath) && $precursive) $directory["folders"][$new_filepath] = self::get_directory($new_filepath,true);
                }
                return $directory;
            } else {
                throw new exception("No folder found at {$folderpath}.");
            }
        }
        catch (exception $e)
        {
            return array();
        }
    }

    static function get_directory_by_pattern($pfolderpath ="./" ,$pregex = "",$precursive = false)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $directory = array("folders"=>array(),"files"=>array(),"path"=>$folderpath);
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_file($new_filepath) && static_file::has_pattern($new_filepath,$pregex)) $directory["files"][$new_filepath] = new file($new_filepath);
                    if(is_dir($new_filepath) && !$precursive) $directory["folders"][$new_filepath] = new object_folder($new_filepath);
                    if(is_dir($new_filepath) && $precursive) $directory["folders"][$new_filepath] = self::get_directory_by_pattern($new_filepath,$pregex,true);
                }
                return $directory;
            } else {
                throw new exception("No folder found at {$folderpath}.");
            }
        }
        catch (exception $e) 
        {
            return array();
        }
    }

    static function get_directory_by_extension($pfolderpath = "./",$pextension = "php",$precursive = false)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $directory = array();
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_file($new_filepath) && static_file::get_extension($new_filepath) === $pextension) $directory["files"][$new_filepath] = new file($new_filepath);
                    if(is_dir($new_filepath) && !$precursive) $directory["folders"][$new_filepath] = new object_folder($new_filepath);
                    if(is_dir($new_filepath) && $precursive) $directory["folders"][$new_filepath] = self::get_directory_by_extension($new_filepath,$pextension,true);
                }
                return $directory;
            } else {
                throw new exception("No folder found at {$folderpath}.");
            }
        }
        catch (Exception $e)
        {
            return array();
        }
    }

    static function get_files($pfolderpath = "./",$precursive = false)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $directory = array();
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_file($new_filepath)) $directory[$new_filepath] = new file($new_filepath);
                    if(is_dir($new_filepath) && $precursive) $directory = array_merge($directory,self::get_files($new_filepath));
                }
                return $directory;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (Exception $e)
        {
            return array();
        }
    }

    static function get_file($pfolderpath ="./",$pfilename)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_file($folderpath.$pfilename)) return new file($folderpath.$pfilename,false);
            throw new exception("File {$folderpath}{$pfilename} doesn't exist.");
        }
        catch (Exception $e)
        {
            return array();
        }
    }

    static function get_files_by_pattern($pfolderpath = "./",$ppattern="",$precursive = false)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $directory = array();
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_file($new_filepath) && static_file::has_pattern($new_filepath,$ppattern)) $directory[$new_filepath] = new file($new_filepath);
                    if(is_dir($new_filepath) && $precursive) $directory = array_merge($directory,self::get_files_by_pattern($new_filepath,$ppattern,true));
                }
                return $directory;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return array();
        }
    }

    static function get_files_by_extension($pfolderpath = "./",$pextension = "php",$precursive = false)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $directory = array();
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_file($new_filepath) && static_file::get_extension($new_filepath) === $pextension) $directory[$new_filepath] = new file($new_filepath);
                    if(is_dir($new_filepath) && $precursive) $directory = array_merge($directory,self::get_files_by_extension($new_filepath,$pextension,true));
                }
                return $directory;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return array();
        }
    }

    static function get_folders($pfolderpath ="./",$precursive = false)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $directory = array();
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == ".." || $file == ".git") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_dir($new_filepath))
                    {
                        if($precursive == true) 
                        {
                            $directory[$new_filepath] = new object_folder($new_filepath);
                            $directory = array_merge($directory,self::get_folders($new_filepath,$precursive));
                        } else {
                            $directory[$new_filepath] = new object_folder($new_filepath);
                        }
                    }
                }
                return $directory;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_folder($pfolderpath ="./",$pfoldername)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_dir($new_filepath) && $file === $pfoldername)
                    {
                        return new object_folder($new_filepath);
                    }
                }
                throw new exception("Folder {$pfoldername} not found within {$folderpath}");
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function create($pfolderpath,$ppermissions = 0755)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_file($folderpath))
            {
                throw new Exception("Permission denied to create folder '{$folderpath}'.");
            }
            if(is_dir($folderpath) || $folderpath === "./" || $folderpath === "/" || $folderpath === "../" || $folderpath === ".." || $folderpath === ".")
            {
                return true;
            } else {
                if(mkdir($folderpath,$ppermissions,true))
                {
                    return true;
                } else {
                    throw new exception("Permission denied to create folder '{$folderpath}'.");
                }
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function remove($pfolderpath)
    {
        try
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                $items = self::get_directory($pfolderpath);
                foreach($items["folders"] as $item)
                {
                    $item->remove();
                }
                foreach($items["files"] as $item)
                {
                    $item->remove();
                }
                return rmdir($folderpath);
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (Exception $e){
            return false;
        }
    }

    static function rename($pfolderpath,$pnew_foldername)
    {
        try
        {
            $old_folderpath = self::get_path($pfolderpath);
            $new_folderpath = self::get_parent_folder($old_folderpath).$pnew_foldername.DS;
            if(self::exist($old_folderpath))
            {
                if(rename($old_folderpath,$new_folderpath))
                {
                    return true;
                } else {
                    throw new exception("Permission denied to rename folder {$pfolderpath}.");
                }
            } else {
                throw new exception("Folder {$old_folderpath} doesn't exist.");
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function import($pfolderpath,$precursive = false)
    {
        try
        {
            $files = self::get_files_by_extension($pfolderpath,"php",$precursive);
            foreach($files as $file)
            {
                if(!$file->import())
                    throw new exception("Failed to import : {$file}");
            }
            return true;
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function has_folder($pfolderpath,$pfoldername)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_dir($new_filepath))
                    {
                        if($file === $pfoldername) return true;
                    }
                }
                return false;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function has_file($pfolderpath,$pfilename)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                foreach(scandir($folderpath) as $file)
                {
                    if($file == "." || $file == "..") continue;
                    $new_filepath = $folderpath.$file;
                    if(is_file($new_filepath))
                    {
                        if($file === $pfilename) return true;
                    }
                }
                return false;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function is_hidden($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                $name = self::get_name($filepath);
                if(substr($name,0,1) === ".") return true;
                return false;
            } else {
                return false;
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function create_file($pfolderpath,$pfilename)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                if(!is_file($folderpath.$pfilename))
                {
                    return static_file::create($folderpath.$pfilename);
                }
                return true;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function create_folder($pfolderpath,$pfoldername)
    {
        try 
        {
            $folderpath = self::get_path($pfolderpath);
            if(is_dir($folderpath))
            {
                if(!is_file($folderpath.$pfoldername))
                {
                    return self::create($folderpath.$pfoldername);
                }
                return true;
            } else {
                throw new exception("Folder {$folderpath} doesn't exist.");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

}
