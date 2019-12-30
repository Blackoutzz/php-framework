<?php
namespace core\backend\filesystem;
use core\common\exception;
use core\common\str;

/**
 * Static File Library.
 *
 * This act as the backbone of the Object File Library and can be used directly.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

abstract class file
{

    static function get_path($pfilepath)
    {
        try
        {
            // Swap every directory separator to right one
            $filepath = preg_replace("~[////\/]~",DIRECTORY_SEPARATOR,$pfilepath);
            // Normal filepath with /
            if(preg_match("~^(?:\.?[\\\\\/]|[\\\\\/]?)(.+)[\\\\\/]?$~im",$filepath,$new_filepath))
            {
                if(preg_match("~^[A-Za-z]\:[\\\\].*~im",$new_filepath[1]))
                    return $new_filepath[1];
                if(preg_match("~^\.[\\\\\/].*$~im",$new_filepath[1]))
                    return $new_filepath[1];
                else
                    return ".".DIRECTORY_SEPARATOR.$new_filepath[1];
            }
            if(preg_match("~^(?:\.?[\\\\\/]|[\\\\\/]?)(.+)[\\\\\/]?$~im",$filepath,$new_filepath))
            {
                if(preg_match("~^[A-Za-z]\:[\\\\].*~im",$new_filepath[1]))
                    return $new_filepath[1];
                if(preg_match("~^\.[\\\\\/].*$~im",$new_filepath[1]))
                    return $new_filepath[1];
                else
                    return ".".DIRECTORY_SEPARATOR.$new_filepath[1];
            }
            throw new exception("File pattern not found for {$filepath}");
        }
        catch(exception $e)
        {
            return $pfilepath;
        }

    }

    static function exist($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(is_file($filepath))
            {
                return true;
            } else {
                throw new exception("File '{$filepath}' doesn't exist");
            }
        }
        catch(exception $e)
        {
            return false;
        }
    }

    static function get_name($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(preg_match('~^.*[\\\\\/]([^\\\\\/]+)$~im',$filepath,$filename))
                {
                    return $filename[1];
                } else {
                    throw new Exception("Impossible to find name pattern within '{$filepath}'.");
                }
            } else {
                return false;
            }
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    static function get_folder($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(is_file($filepath)){
                if(preg_match('~^(.*[\\\\\/])[^\\\\\/]+$~im',$filepath,$folderpath))
                {
                    if(is_dir($folderpath[1]))
                    {
                        return $folderpath[1];
                    } else {
                        throw new Exception("Invalid match '{$folderpath[1]}' is not a folder.");
                    }
                } else {
                    throw new exception("File {$filepath} doesn't seems to have a folder");
                }
            } else {
                return false;
            }
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    static function get_size($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(is_file($filepath))
            {
                $size = filesize($filepath);
                $units = array(
                    "TB" => 1099511627776,
                    "GB" => 1073741824,
                    "MB" => 1048576,
                    "KB" => 1024,
                    "B"  => 1
                );
                foreach ($units as $unit => $usize)
                {
                    if($size == $usize)
                        return "1{$unit}";
                    if($size > $usize)
                        return round(($size/$usize),2).$unit;
                }
                return $size."B";
            } else {
                throw new exception("File {$filepath} doesn't exist");
            }
        }
        catch(exception $e)
        {
            return false;
        }
    }

    static function get_owner($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(function_exists("posix_getpwuid"))
                {
                    return posix_getpwuid(fileowner($filepath))["name"];
                } else {
                    return fileowner($filepath);
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function is_executable($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath) && is_executable($filepath))
            {
                return true;
            }
            return false;
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function is_writable($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath) && is_writable($filepath))
            {
                return true;
            }
            return false;
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function is_readable($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath) && is_readable($filepath))
            {
                return true;
            }
            return false;
        }
        catch (Exception $e) 
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

    static function get_permissions($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(is_file($filepath))
            {
                return substr(sprintf('%o', fileperms($filepath)),-4);
            } else {
                return false;
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function set_permissions($pfilepath,$ppermissions = 0644)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(chmod($filepath,$ppermissions))
                {
                    touch($filepath);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function set_permission_mode($pfilepath,$pmode = "default")
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            $mode = $pmode;
            if(self::exist($filepath))
            {
                if($mode === "default" || $mode === file_permission::normal)
                {
                    //All set RW 0644
                    if(self::set_permissions($filepath,0644))
                    {
                        return true;
                    }
                }
                if($mode === "read-only" || $mode === file_permission::read_only)
                {
                    //0444 Read-only
                    if(self::set_permissions($filepath,0444))
                    {
                        return true;
                    }
                }
                if($mode === "write-only" || $mode === file_permission::write_only)
                {
                    //0222 Write-only
                    if(self::set_permissions($filepath,0222))
                    {
                        return true;
                    }
                }
                if($mode === "execute-only" || $mode === file_permission::execute_only)
                {
                    //0111 Execute-Only
                    if(self::set_permissions($filepath,0111))
                    {
                        return true;
                    }
                }
                if($mode === "access-denied" || $mode === file_permission::restricted)
                {
                    //0000 No-Permissions
                    if(self::set_permissions($filepath,0000))
                    {
                        return true;
                    }
                }
                throw new exception("Invalid file permission used as {$mode}");
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_contents($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(is_readable($filepath))
                {
                    $contents = file_get_contents($filepath);
                    if($contents !== false)
                    {
                        return trim($contents);
                    } else {
                        throw new Exception("Empty file {$filepath}");
                    }
                } else {
                    throw new Exception("Permission denied to read file {$filepath}");
                }
            } else {
                return "";
            }
        }
        catch (exception $e) 
        {
            return "";
        }
    }

    static function add_contents($pfilepath,$pcontent)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(!is_writable($filepath))
                {
                    throw new exception("Permission denied to write to file {$filepath}");
                }
                if(file_put_contents($filepath,$pcontent,FILE_APPEND) !== false)
                {
                    return true;
                } else {
                    throw new exception("Error while trying to write to file {$filepath}");
                }
            } else {
                return false;
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function set_contents($pfilepath,$pcontent = '')
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(!self::is_writable($filepath)) return false;
                if(file_put_contents($filepath,$pcontent) !== false)
                {
                    return true;
                } else {
                    throw new Exception("Permission denied to write to file {$filepath}");
                }
            } else {
                return false;
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function create($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(!self::exist($filepath))
            {
                if(file_put_contents($filepath,"") === false)
                {
                    throw new exception("Permission denied to write to file {$filepath}");
                }
                touch($filepath);
                return true;
            } else {
                touch($filepath);
                return true;
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function remove($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(unlink($filepath))
                {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function copy($pfilepath,$pnew_filepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            $new_filepath = self::get_path($pnew_filepath);
            if(self::exist($filepath))
            {
                if(copy($filepath,$new_filepath))
                {
                    if(filesize($filepath) === filesize($new_filepath))
                    {
                        touch($filepath);
                        touch($new_filepath);
                        return true;
                    } else {
                        self::remove($new_filepath);
                        throw new exception("Copied filesize doesn't match.");
                    }
                } else {
                    throw new exception("Permission denied to copy {$filepath}.");
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }

    }

    static function move($pfilepath,$pnew_filepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            $new_filepath = self::get_path($pnew_filepath);
            $upload_dir = ini_get("upload_tmp_dir");
            if(preg_match("~^{$upload_dir}.+$~im",$pfilepath))
            {
                return move_uploaded_file($pfilepath,$new_filepath);  
            }
            elseif(self::exist($filepath))
            {
                if(self::rename($filepath,$new_filepath))
                {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_lines($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(self::is_readable($filepath))
                {
                    $content = file_get_contents($filepath);
                    if($content){
                        return explode("\n",$content);
                    } else {
                        throw new exception("No line found into file {$filepath}");
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_line_by_id($pfilepath,$pindex = 1)
    {
        try
        {
            $index = intval($pindex);
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath) && self::is_readable($filepath))
            {
                $content = file_get_contents($filepath);
                if($content)
                {
                    $lines = explode("\n",$content);
                    if(count($lines) >= $index)
                    {
                        return $lines[$index-1];
                    } else {
                        throw new exception("Line {$index} doesn't exist into file {$filepath}");
                    }
                } else {
                    throw new exception("No line found into file {$filepath}");
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function get_extension($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(preg_match("~^.*\.([A-z0-9]+)$~im",$filepath,$extension))
                {
                    return $extension[1];
                } else {
                    throw new exception("File {$filepath} doesn't have any extension");
                }
            } else {
                return "";
            }
        }
        catch (exception $e)
        {
            return "";
        }
    }

    static function get_extension_icon($pfilepath,$psize = 1)
    {
        $size = intval($psize);
        $extension = strtolower(self::get_extension($pfilepath));
        //Code
        if($extension === "php"
        || $extension === "htm"
        || $extension === "html"
        || $extension === "xhtml"
        || $extension === "shtml"
        || $extension === "js"
        || $extension === "c"
        || $extension === "go"
        || $extension === "htaccess"
        || $extension === "lua"
        || $extension === "asp"
        || $extension === "py"
        || $extension === "css"){
            return "<i class=\"fa fa-file-code-o fa-{$size}x\"></i>";
        }
        //Picture
        if($extension === "gif"
        || $extension === "jpeg"
        || $extension === "tiff"
        || $extension === "png"
        || $extension === "bmp"
        || $extension === "jpg"
        || $extension === "svg"
        || $extension === "bpg"
        || $extension === "exif"
        || $extension === "ico"){
            return "<i class=\"fa fa-file-image-o fa-{$size}x\"></i>";
        }
        //PDF
        if($extension === "pdf"){
            return "<i class=\"fa fa-file-pdf-o fa-{$size}x\"></i>";
        }
        //text
        if($extension === "text"
        || $extension === "txt"
        || $extension === "json"){
            return "<i class=\"fa fa-file-text-o fa-{$size}x\"></i>";
        }
        //compressed library
        if($extension === "rar"
        || $extension === "gzip"
        || $extension === "zip"
        || $extension === "tar"
        || $extension === "gz"){
            return "<i class=\"fa fa-file-archive-o fa-{$size}x\"></i>";
        }
        //Exel
        if($extension === "csv"
        || $extension === "exel"){
            return "<i class=\"fa fa-file-excel-o fa-{$size}x\"></i>";
        }
        //word
        if($extension === "word"){
            return "<i class=\"fa fa-file-word-o fa-{$size}x\"></i>";
        }
        //Video
        if($extension === "flv"
        || $extension === "f4v"
        || $extension === "avi"
        || $extension === "webm"
        || $extension === "wmv"
        || $extension === "mp4"
        || $extension === "m4v"
        || $extension === "mpeg"
        || $extension === "mpg"
        || $extension === "mov"){
            return "<i class=\"fa fa-file-video-o fa-{$size}x\"></i>";
        }
        //Audio
        if($extension === "mp3"
        || $extension === "wma"
        || $extension === "wav"
        || $extension === "m4a"
        || $extension === "m4p"
        || $extension === "flac"
        || $extension === "au"
        || $extension === "aac"
        || $extension === "aiff"
        || $extension === "msv"){
            return "<i class=\"fa fa-file-audio-o fa-{$size}x\"></i>";
        }
        return "<i class=\"fa fa-file-o fa-{$size}x\"></i>";
    }

    static function get_extension_type($pfilepath)
    {
        $extension = strtolower(self::get_extension($pfilepath));
        //Code
        if($extension === "php"
        || $extension === "htm"
        || $extension === "html"
        || $extension === "xhtml"
        || $extension === "shtml"
        || $extension === "js"
        || $extension === "c"
        || $extension === "go"
        || $extension === "htaccess"
        || $extension === "lua"
        || $extension === "asp"
        || $extension === "py"
        || $extension === "css")
        {
            return 'Script/Code';
        }
        //Picture
        if($extension === "gif"
        || $extension === "jpeg"
        || $extension === "tiff"
        || $extension === "png"
        || $extension === "bmp"
        || $extension === "jpg"
        || $extension === "svg"
        || $extension === "bpg"
        || $extension === "exif")
        {
            return 'Image/Picture';
        }
        //PDF
        if($extension === "pdf")
        {
            return 'PDF/Document';
        }
        //text
        if($extension === "text"
        || $extension === "txt"
        || $extension === "md"
        )
        {
            return 'Text/Note';
        }
        //compressed library
        if($extension === "rar"
        || $extension === "gzip"
        || $extension === "zip"
        || $extension === "tar"
        || $extension === "gz")
        {
            return 'Compressed/Files';
        }
        //Exel
        if($extension === "csv"
        || $extension === "exel")
        {
            return 'Exel/CSV';
        }
        //word
        if($extension === "word")
        {
            return 'Word/Text';
        }
        //Video
        if($extension === "flv"
        || $extension === "f4v"
        || $extension === "avi"
        || $extension === "webm"
        || $extension === "wmv"
        || $extension === "mp4"
        || $extension === "m4v"
        || $extension === "mpeg"
        || $extension === "mpg"
        || $extension === "mov")
        {
            return 'Video/Movie';
        }
        //Audio
        if($extension === "mp3"
        || $extension === "wma"
        || $extension === "wav"
        || $extension === "m4a"
        || $extension === "m4p"
        || $extension === "flac"
        || $extension === "au"
        || $extension === "aac"
        || $extension === "aiff"
        || $extension === "msv")
        {
            return 'Music/Audio';
        }
        return 'Unkown';
    }

    static function get_file_type()
    {
        //TODO
    }

    static function has_pattern($pfilepath,$pregex)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                $text = self::get_contents($filepath);
                if(str::has_pattern($text,$pregex))
                {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

    static function import($pfilepath)
    {
        try
        {
            $filepath = self::get_path($pfilepath);
            if(self::exist($filepath))
            {
                if(self::get_extension($filepath) === "php"
                || self::get_extension($filepath) === "php4"
                || self::get_extension($filepath) === "php5"
                || self::get_extension($filepath) === "php7")
                {
                    if(include($filepath))
                    {
                        return true;
                    } else {
                        throw new exception("File {$filepath} cannot be included.");
                    }
                } else {
                    throw new exception("File {$pfilepath} , only php can be imported");
                }
            } else {
                return false;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    static function unzip($pzip_filepath,$pdestination_filepath)
    {
        try 
        {
            $zip = new \ZipArchive;
            $res = $zip->open($pzip_filepath);
            if ($res === true){
                $zip->extractTo($pdestination_filepath);
                $zip->close();
                return true;
            } else {
                if($res == \ZipArchive::ER_OPEN){
                    throw new exception("Error couldn't open the file.");
                }
                throw new exception("Unknown error by unziping the file.");
            }
        }
        catch (exception $e) 
        {
            return false;
        }

    }

    static function rename($pfilepath,$pnew_name)
    {
        try
        {
            $old_filepath = self::get_path($pfilepath);
            $new_filepath = self::get_folder($old_filepath).$pnew_name.DS;
            if(self::exist($old_filepath))
            {
                if(rename($old_filepath,$new_filepath))
                {
                    return true;
                } else {
                    throw new exception("Permission denied to rename file {$old_filepath}");
                }
            } else {
                return false;
            }
        }
        catch (exception $e) 
        {
            return false;
        }
    }

}
