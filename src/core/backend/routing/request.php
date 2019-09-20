<?php
namespace core\backend\routing;

class request 
{

    protected $parameters;

    protected $type; 

    public function __construct()
    {
        $this->parameters = array();
        $this->parse_from_server();
    }

    protected function parse_from_server()
    {
        $url = trim(urldecode($_SERVER['REQUEST_URI']),"/");
        $url = preg_replace('~\?.*~i','',$url);
        $params = explode("/",$url);
        foreach($params as $param)
        {
            if(!preg_match('~(\?[A-z0-9_-]*=.*|&[A-z0-9_-]*=.*)~i',$param))
            {
                if($param != "" && preg_match('~([A-Za-z0-9_-]*)~i',$param))
                {
                    $this->parameters[] = $param;
                }
            }
        }
        if($defined_type = $this->parse_type_from_parameters($this->parameters))
        {
            $parameters = array();
            $parameter_id = 1;
            while(isset($this->parameters[$parameter_id]))
            {
                $parameters[] = $this->parameters[$parameter_id];
                $parameter_id++;
            }
            $this->parameters = $parameters;
        }
    }

    protected function parse_type_from_parameters($pparameters)
    {
        if(is_array($pparameters) && count($pparameters) >= 1)
        {
            $ptype = strtolower($pparameters[0]);
            switch($ptype)
            {
                case 'page':
                    $this->type = "page";
                    return true;
                case 'ajax':
                    $this->type = "ajax";
                    return true;
                case 'rss':
                    $this->type = "rss";
                    return true;
                case 'api':
                    $this->type = "api";
                    return true;
                case 'cron':
                    $this->type = "cron";
                    return true;
                default:
                    $this->type ="page";
                    return false;
            }
        } else {
            $this->type = "page";
            return false;
        }
    }

    public function get_parameters()
    {
        return $this->parameters;
    }

    public function get_type()
    {
        return $this->type;
    }

}
