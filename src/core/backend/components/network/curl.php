<?php 
namespace core\backend\components;
use core\component;
use core\common\exception;
use core\backend\network\curl\parser;
use core\backend\network\curl\request;

class curl extends component
{

    public function create_get_request($purl,$pheaders = array(),$pcookies = array())
    {
        try
        {
            $parameters = array();
            if($purl)
            {
                $parameters["request"] = "GET";
                if(is_array($pheaders) && count($pheaders) >= 1) $parameters["header"] = $pheaders;
                if(is_array($pcookies) && count($pcookies) >= 1) $parameters["cookie"] = $pcookies;
                return new request($parameters);
            } else {
                throw new exception("Unable to create request without url.");
            }
        } 
        catch (exception $e)
        {
            return $this->on_request_error($e->get_message());
        }
    }

    public function create_post_request($purl,$pheaders = array(),$pcookies = array(),$pdata = array())
    {
        try
        {
            $parameters = array();
            if($purl)
            {
                $parameters["request"] = "POST";
                if(is_array($pheaders) && count($pheaders) >= 1) $parameters["header"] = $pheaders;
                if(is_array($pcookies) && count($pcookies) >= 1) $parameters["cookie"] = $pcookies;
                if(is_array($pdata) && count($pdata) >= 1) $parameters["data"] = $pdata;
                return new request($parameters);
            } else {
                throw new exception("Unable to create request without url.");
            }
        } catch (exception $e)
        {
            return $this->on_request_error($e->get_message());
        }
    }

    public function create_put_request($purl,$pheaders = array(),$pcookies = array(),$pdata = array())
    {
        try
        {
            $parameters = array();
            if($purl)
            {
                $parameters["request"] = "PUT";
                if(is_array($pheaders) && count($pheaders) >= 1) $parameters["header"] = $pheaders;
                if(is_array($pcookies) && count($pcookies) >= 1) $parameters["cookie"] = $pcookies;
                if(is_array($pdata) && count($pdata) >= 1) $parameters["data"] = $pdata;
                return new request($parameters);
            } else {
                throw new exception("Unable to create request without url.");
            }
        } catch (exception $e)
        {
            return $this->on_request_error($e->get_message());
        }
    }

    public function create_form_request($purl,$pheaders = array(),$pcookies = array(),$pdata = array())
    {
        try
        {
            $parameters = array();
            if($purl)
            {
                $parameters["request"] = "POST";
                if(is_array($pheaders) && count($pheaders) >= 1) $parameters["header"] = $pheaders;
                if(is_array($pcookies) && count($pcookies) >= 1) $parameters["cookie"] = $pcookies;
                if(is_array($pdata) && count($pdata) >= 1) $parameters["form"] = $pdata;
                return new request($parameters);
            } else {
                throw new exception("Unable to create request without url.");
            }
        } catch (exception $e)
        {
            return $this->on_request_error($e->get_message());
        }
    }

    public function create_delete_request($purl,$pheaders = array(),$pcookies = array())
    {
        try
        {
            $parameters = array();
            if($purl)
            {
                $parameters["request"] = "DELETE";
                if(is_array($pheaders) && count($pheaders) >= 1) $parameters["header"] = $pheaders;
                if(is_array($pcookies) && count($pcookies) >= 1) $parameters["cookie"] = $pcookies;
                return new request($parameters);
            } else {
                throw new exception("Unable to create request without url.");
            }
        } catch (exception $e)
        {
            return $this->on_request_error($e->get_message());
        }
    }

    public function get_request_from_cmdline($pcmd)
    {
        $parser = new parser();
        return $parser->get_request_from_cmdline($pcmd);
    } 

    protected function on_request_error($pmsg)
    {
        return false;
    }

}