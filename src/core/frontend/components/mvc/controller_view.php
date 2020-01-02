<?php
namespace core\frontend\components\mvc;
use core\frontend\components\template;
use core\backend\components\file;
use core\frontend\components\widget;
use core\common\exception;
use core\program;

/**
 * Controller view 
 *
 * Frontend display of the backend
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class controller_view extends template
{

    protected $reference;

    protected $view_data;

    protected $cache;

    public function __construct($preference,$pcache,$pview_data)
    {
        $this->reference = $preference;
        $this->view_data = $pview_data;
        $this->cache = $pcache;
    }

    protected function get_data($pname = false)
    {
        try
        {
            if($pname === false)
            {
                return $this->view_data;
            } else {
                foreach($this->view_data as $key => $data)
                {
                    if($key === $pname)
                    {
                        return $data;
                    }
                }
                throw new exception("No data named {$pname} found.");
            }
        } 
        catch (exception $e) 
        {
            return false;
        }
    }

    protected function get_widget($pwidget,$pdata = array())
    {
        $widget = (string) $pwidget;
        if(preg_match("~^[a-z\\\_]+$~im",$widget))
        {
            $widget_class = "core\\frontend\\components\\widgets\\{$widget}";
            if(class_exists($widget_class))
            {
                $widget = new $widget_class($pdata);
                if($widget instanceof widget) return $widget;
            }
        }
        return "";
    }

    public function load_layout()
    {
        try
        {
            $layout = "dashboard";
            if(!include("ui".DS."themes".DS.$layout.DS."index.php"))
            {
                throw new exception("Impossible to load application theme named '{$layout}'.");
            }
            return true;
        } 
        catch (exception $e)
        {
            return false;
        }
    }

    protected function load_script($name="")
    {
        try
        {
            $controller_name = $this->get_controller_name();
            $view_name = $this->get_view_name();
            if($this->reference === "core")
            {
                if(is_file("assets".DS."scripts".DS.$controller_name.DS.$view_name.".js"))
                {
                    if($name != "")
                    {
                        echo ("<script src='assets/scripts/{$name}.js'></script>");
                    } else {
                        echo ("<script src='assets/scripts/".$controller_name."/".$view_name.".js'></script>");
                    }
                }
                return true;
            } else {
                if(is_file("plugins".DS.$this->reference.DS."assets".DS."scripts".DS.$controller_name.DS.$view_name.".js"))
                {
                    if($name != "")
                    {
                        echo ("<script src='plugins/{$this->reference}/assets/scripts/{$name}.js'></script>");
                    } else {
                        echo ("<script src='plugins/{$this->reference}/assets/scripts/".$controller_name."/".$view_name.".js'></script>");
                    }
                }
                return true;
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    public function load_view()
    {
        try
        {
            $controller_name = $this->get_controller_name();
            $view_name = $this->get_view_name();
            if($this->reference === "core")
            {
                if(!include("ui".DS."views".DS.$controller_name.DS.$view_name.".php"))
                {
                    $new_view = new file("ui".DS."views".DS.$controller_name.DS.$view_name.".php");
                    $new_view->set_contents("<div class='alert alert-warning'>Brand new page without content.</div>");
                    if(!include("ui".DS."views".DS.$controller_name.DS.$view_name.".php"))
                       throw new exception("Impossible to load view on '{$controller_name}/{$view_name}'.");
                }
                $this->load_script();
                return true;
            } else {
                if(!$this->cache->is_saved() || !$this->cache->is_required())
                {
                    $this->push();
                    if(!include("plugins".DS.$this->reference.DS."ui".DS."views".DS.$controller_name.DS.$view_name.".php"))
                    {
                        $new_view = new file("plugins".DS.$this->reference.DS."ui".DS."views".DS.$controller_name.DS.$view_name.".php");
                        $new_view->set_contents("<div class='alert alert-warning'>Brand new page without content.</div>");
                        if(!include("plugins".DS.$this->reference.DS."ui".DS."views".DS.$controller_name.DS.$view_name.".php"))
                            throw new exception("Impossible to load view on '{$controller_name}/{$view_name}'.");
                    }
                    $this->load_script();
                    if(!$this->cache->is_saved() && $this->cache->is_required())
                    {
                        $this->cache->save_view();
                    }
                } else {
                    if(!$this->cache->restore_saved_view())
                    {
                        if(!include("plugins".DS.$this->reference.DS."ui".DS."views".DS.$controller_name.DS.$view_name.".php"))
                        {
                            $new_view = new file("plugins".DS.$this->reference.DS."ui".DS."views".DS.$controller_name.DS.$view_name.".php");
                            $new_view->set_contents("<div class='alert alert-warning'>Brand new page without content.</div>");
                            if(!include("plugins".DS.$this->reference.DS."ui".DS."views".DS.$controller_name.DS.$view_name.".php"))
                                throw new exception("Impossible to load view on '{$controller_name}/{$view_name}'.");
                        }
                        $this->load_script();
                    }
                }

                return true;
            }
        } 
        catch (exception $e)
        {
            return false;
        }
    }

    public function load_tab($pname = false)
    {
        try{
            $controller_name = $this->get_controller_name();
            $view_name = $this->get_view_name();
            if($this->reference === "core")
            {
                if($pname === false)
                {
                    if(!include("ui".DS."tabs".DS.$controller_name.DS.$view_name.DS."index.php"))
                    {
                        $new_view = new file("ui".DS."tabs".DS.$controller_name.DS.$view_name.DS."index.php");
                        $new_view->set_contents("<div class='alert alert-warning'>Brand new tab without content.</div>");
                        if(!include("ui".DS."tabs".DS.$controller_name.DS.$view_name.DS."index.php"))
                            throw new exception("Impossible to load tab on '{$controller_name}/{$view_name}'.");
                    }
                } else {
                    if(!include("ui".DS."tabs".DS.$controller_name.DS.$view_name.DS.$pname.".php"))
                    {
                        $new_view = new file("ui".DS."tabs".DS.$controller_name.DS.$view_name.DS.$pname.".php");
                        $new_view->set_contents("<div class='alert alert-warning'>Brand new tab without content.</div>");
                        if(!include("ui".DS."tabs".DS.$controller_name.DS.$view_name.DS.$pname.".php"))
                            throw new exception("Impossible to load tab on '{$controller_name}/{$view_name}/{$pname}'.");
                    }
                }
                return true;
            } else {
                if($pname === false)
                {
                    if(!include("plugins".DS.$this->reference.DS."interface".DS."tabs".DS.$controller_name.DS.$view_name.".php"))
                    {
                        $new_view = new file("plugins".DS.$this->reference.DS."ui".DS."tabs".DS.$controller_name.DS.$view_name.".php");
                        $new_view->set_contents("<div class='alert alert-warning'>Brand new tab without content.</div>");
                        if(!include("plugins".DS.$this->reference.DS."ui".DS."tabs".DS.$controller_name.DS.$view_name.".php"))
                            throw new exception("Impossible to load tab on '{$controller_name}/{$view_name}'.");
                    }
                } else {
                    if(!include("plugins".DS.$this->reference.DS."interface".DS."tabs".DS.$controller_name.DS.$view_name.DS.$pname.".php"))
                    {
                        $new_view = new file("plugins".DS.$this->reference.DS."ui".DS."tabs".DS.$controller_name.DS.$view_name.DS.$pname.".php");
                        $new_view->set_contents("<div class='alert alert-warning'>Brand new tab without content.</div>");
                        if(!include("plugins".DS.$this->reference.DS."ui".DS."tabs".DS.$controller_name.DS.$view_name.DS.$pname.".php"))
                            throw new exception("Impossible to load tab on '{$controller_name}/{$view_name}/{$pname}'.");
                    }
                }
                return true;
            }
        } 
        catch (exception $e)
        {
            return false;
        }
    }

}
