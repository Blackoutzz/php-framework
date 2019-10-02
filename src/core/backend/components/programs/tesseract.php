<?php
namespace core\backend\programs;
use core\common\str;
use core\backend\system\console;
use core\backend\system\os;

/**
 * tesseract short summary.
 *
 * tesseract description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class tesseract extends console
{
    
    protected $installed;
    
    protected $last_error;
    
    /*
    -psm
    0    Orientation and script detection (OSD) only.
    1    Automatic page segmentation with OSD.
    2    Automatic page segmentation, but no OSD, or OCR.
    3    Fully automatic page segmentation, but no OSD. (Default)
    4    Assume a single column of text of variable sizes.
    5    Assume a single uniform block of vertically aligned text.
    6    Assume a single uniform block of text.
    7    Treat the image as a single text line.
    8    Treat the image as a single word.
    9    Treat the image as a single word in a circle.
    10   Treat the image as a single character.
    11   Sparse text. Find as much text as possible in no particular order.
    12   Sparse text with OSD.
    13   Raw line. Treat the image as a single text line, bypassing hacks that are Tesseract-specific.
     */
    protected $psm = 6;
    
    /*
    --oem
    0    Legacy engine only.
    1    Neural nets LSTM engine only.
    2    Legacy + LSTM engines.
    3    Default, based on what is available.
     */
    protected $oem = 3;
    
    protected function on_windows()
    {
        if($this->execution_path == "") $this->execution_path = "C:\\Program Files (x86)\\Tesseract-OCR\\";
        $this->application = "tesseract.exe";
        if(is_file($this->execution_path.$this->application)) $this->installed = true;
    }
    
    protected function on_unix()
    {
        $this->application = "tesseract";
        $this->execution_path = ":/usr/local/bin:/usr/local/sbin:/usr/bin:/bin:/usr/sbin:/sbin:";
        if(preg_match("~tesseract ([0-9\.\-\_A-z]+)\n~im",shell_exec("tesseract -v"))) $this->installed = true;
    }
    
    protected function on_macos()
    {
        $this->application = "tesseract";
        $this->execution_path = ":/usr/local/bin:/usr/local/sbin:/usr/bin:/bin:/usr/sbin:/sbin:";
        if(preg_match("~tesseract ([0-9\.\-\_A-z]+)\n~im",shell_exec("tesseract -v"))) $this->installed = true;
    }
    
    public function __toString()
    {
        if($this->installed)
        {
            $full_version = $this->execute(array("-v"));
            if(preg_match("~tesseract ([0-9\.\-\_A-z]+)\n~im",$full_version,$version)) return $version[0];
            return "tesseract unknown version";
        } else {
            return "tesseract isn't installed";
        }

    }
    
    public function is_installed()
    {
        return $this->installed;
    }
    
    public function get_version()
    {
        if($this->installed)
        {
            $full_version = $this->execute(array("-v"));
            if(preg_match("~tesseract ([0-9\.\-\_A-z]+)\n~im",$full_version,$version)) return $version[1];
            return "Unknown version";
        }
        return "No version found";
    }
    
    public function get_text_from_image($pimage)
    {
        if(is_file("{$pimage}"))
        {
            $tesseract_params = array(
                "{$pimage}",
                "stdout",
                "-l"=>"eng",
                "--psm"=>$this->psm,
                "--oem"=>$this->oem
            );
            $output = trim(trim($this->execute($tesseract_params)),"\x0c\x0a");
            if(preg_match("~(Warning|Error)\.* *(.*)~im",$output,$error))
            {
                $this->last_error = $error[2];
                $output = str_replace($error[0],"",$output);
            }
            return $output;
        }
        return "";
    }
    
}