<?php
namespace core\backend\components\mvc\controllers;
use core\backend\components\mvc\controller;
use core\common\exception;
use core\backend\database\mysql\model;
use core\backend\database\mysql\datasets\user;
use core\backend\database\dataset;
use core\backend\database\dataset_array;

/**
 * API Controller.
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class api extends controller
{

    protected $connected = false;

    public function initialize()
    {
        try
        {
            if($this->has_view())
            {
                if($data = $this->prepare_view())
                {
                    if(isset($_REQUEST["format"]))
                    {
                        $format = strtolower($_REQUEST["format"]);
                        switch($format)
                        {
                            case 'csv':
                                $this->on_csv_output($data);
                                break;
                            case 'xml':
                                $this->on_xml_output($data);
                                break;
                            case 'json':
                                $this->on_json_output($data);
                                break;
                            default:
                                $this->on_json_output($data);
                        }
                    } else {
                        $this->on_json_output($data);
                    }
                } else {
                    throw new exception("Api returned no data",503);
                } 
            } else {
                throw new exception("Invalid api path",404);
            }
        } 
        catch (exception $e)
        {
            $data = array("msg"=>$e->get_message(),"code"=>$e->get_code());
            if(isset($_REQUEST["format"]))
            {
                $format = strtolower($_REQUEST["format"]);
                switch($format)
                {
                    case 'csv':
                        $this->on_csv_output($data);
                        break;
                    case 'xml':
                        $this->on_xml_output($data);
                        break;
                    case 'json':
                        $this->on_json_output($data);
                        break;
                    default:
                        $this->on_json_output($data);
                }
            } else {
                $this->on_json_output($data);
            }
        }
    }

    protected function has_view()
    {
        try
        {
            
            $view = trim(strtolower(str_replace("-","_",$this->get_view_name())));
            if(preg_match('~^([A-z]+[A-z-_]*[A-z]+)$~im',$view))
            {
                if(method_exists('core\\backend\\components\\mvc\\controllers\\api',$view)) 
                    throw new exception("Reserved view name");
                if(method_exists($this,$view))
                {
                    return true;
                } else {
                    throw new exception("No view configured inside controller");
                }
            } else {
                throw new exception("Invalid view name");
            }
        }
        catch (exception $e)
        {
            return false;
        }
    }

    protected function on_json_output($pdata)
    {
        try
        {
            header("Content-Type: text/json");
            if($pdata instanceof dataset)
            {
                print($pdata->__toJson());
            } 
            elseif($pdata instanceof dataset_array)
            {
                print($pdata->__toJson());
            }
            else
            {
                if($pdata)
                {
                    if(is_string($pdata) || is_integer($pdata) || is_bool($pdata)) $data = array($pdata); 
                    elseif (is_array($pdata)) $data = $pdata;
                    if(isset($data))
                    {
                        print(json_encode($data));
                    } else {
                        
                        throw new exception("Invalid output data",503);
                    }
                } else {
                    
                    throw new exception("Invalid format data",503);
                }
            }
        }
        catch(exception $e)
        {
            $data = array("msg"=>$e->get_message(),"code"=>$e->get_code());
            print(json_encode($data));
        }
    }

    protected function on_xml_output($pdata)
    {
        try
        {
            header("Content-Type: text/xml");
            if($pdata instanceof dataset)
            {
                print($pdata->__toXML());
            } 
            elseif($pdata instanceof dataset_array)
            {
                print($pdata->__toXML());
            }
            else
            {
                throw new exception("Invalid data format",503);
            }
        }
        catch(exception $e)
        {
            $data = array("msg"=>$e->get_message(),"code"=>$e->get_code());
            print(json_encode($data));
        }
    }

    protected function on_csv_output($pdata)
    {
        try
        {
            header("Content-Type: text/csv");
            if($pdata instanceof dataset)
            {
                print($pdata->__toCSV());
            } 
            elseif($pdata instanceof dataset_array)
            {
                print($pdata->__toCSV());
            } 
            else 
            {
                throw new exception("Invalid data format",503);
            }
            
        }
        catch(exception $e)
        {
            $data = array("msg"=>$e->get_message(),"code"=>$e->get_code());
            print(json_encode($data));
        }
        
    }

    protected function on_requirement_failed()
    {
        http_response_code(403);
        die(json_encode(array("code"=>403,"message"=>"Permission denied to access the API.")));
    }

    protected function require_login()
    {
        try
        {
            if(isset($_REQUEST["key"]))
            {
                $api_key = $_REQUEST["key"];
                $user_option = model::get_user_option_by_option_and_value("api-key",$api_key);
                if(model::is_user_option($user_option))
                {
                    if($user_option->get_value() === $api_key)
                    {
                        $this->connected = true;
                        return true;
                    }
                }
                throw new exception("API: Invalid API Key.");
            } else {
                throw new exception("API: No key found.");
            }
        }
        catch(exception $e)
        {
            $this->on_requirement_failed();
            return false;
        }
    }

    protected function is_login()
    {
        return $this->connected;
    }

    protected function get_user()
    {
        if(isset($_REQUEST["key"]))
        {
            $api_key = $_REQUEST["key"];
            $user_option = model::get_user_option_by_option_and_value("api-key",$api_key);
            if(model::is_user_option($user_option))
            {
                if($user_option->get_value() === $api_key)
                {
                    return $user_option->get_user();
                }
            }
        }
        return new user(array("id"=>0,"name"=>"Visitor","user_group"=>2));
    }

    public function redirect($ppath = "")
    {
        try 
        {
            if($ppath != "")
            {
                if(isset($_REQUEST["key"]) && isset($_REQUEST["output"]) && isset($_REQUEST["depth"]) && $this->is_login()) header("Location: /api/{$ppath}?key=".urlencode($_REQUEST["key"])."&output=".urlencode($_REQUEST["output"])."&depth");
                elseif(isset($_REQUEST["key"]) && isset($_REQUEST["output"]) && $this->is_login()) header("Location: /api/{$ppath}?key=".urlencode($_REQUEST["key"])."&output=".urlencode($_REQUEST["output"]));
                elseif(isset($_REQUEST["key"]) && $this->is_login()) header("Location: /api/{$ppath}?key=".urlencode($_REQUEST["key"]));
                else header("Location: /api/{$ppath}");
            }
            die();
        }
        catch (exception $e)
        {
            die();
        }
    }

}
