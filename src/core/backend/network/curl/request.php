<?php
namespace core\backend\network\curl;
use core\common\regex;

/**
 * curl request.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class request
{
    
    protected $request;

    protected $headers;

    protected $posts;

    protected $options;

    protected $result;

    public function __construct($poptions)
    {
        $this->options = $poptions;
        $this->posts = new posts();
        $this->headers = new headers();
        $this->request = curl_init();
        $this->set_default_options();
        if(is_array($poptions) && count($poptions) >= 1)
        {
            foreach($poptions as $option_name => $option_value)
            {
                switch(strtolower($option_name))
                {
                    case "proxy":
                        $this->set_proxy($option_value);
                        break;
                    case "data":
                        $this->set_data($option_value);
                        break;
                    case "url":
                        $this->set_url($option_value);
                        break;
                    case "user-agent":
                        $this->set_user_agent($option_value);
                        break;
                    case "header":
                        $this->set_header($option_value);
                        break;
                    case "max-time":
                        $this->set_max_time($option_value);
                        break;
                    case "connect-timeout":
                        $this->set_connect_timeout($option_value);
                        break;
                    case "insecure":
                        $this->set_insecure_mode($option_value);
                        break;
                    case "form":
                        $this->set_form($option_value);
                        break;
                    case "cookie":
                        $this->set_cookie($option_value);
                        break;
                    case "request":
                        $this->set_custom_request($option_value);
                        break;
                }
            }
            if(!array_key_exists("insecure",$poptions)) $this->set_insecure_mode(true);
            if(!array_key_exists("max-time",$poptions)) $this->set_max_time(3);
            if(!array_key_exists("connect-timeout",$poptions)) $this->set_connect_timeout(3);
        }
    }

    public function __destruct()
    {
        curl_close($this->request);
    }

    public function send()
    {
        if(!$this->result instanceof request_result)
        {
            if(count($this->headers) >= 1)
            {
                curl_setopt($this->request,CURLOPT_HTTPHEADER,$this->headers->get_http_headers());
            }
            if(count($this->posts) >= 1)
            {
                curl_setopt($this->request,CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($this->request,CURLOPT_POSTREDIR,3);
                if($this->posts->contains_form() || $this->posts->contains_file_upload())
                {
                    $this->headers["Content-Type"] = "Content-Type: multipart/form-data";
                }
                else
                {
                    $this->headers["Content-Type"] = "Content-Type: application/x-www-form-urlencoded";
                }

                curl_setopt($this->request,CURLOPT_POST,true);
                curl_setopt($this->request,CURLOPT_POSTFIELDS,$this->posts->get_postfields());
            }
            $response = curl_exec($this->request);
            //Extract Result
            $header_size = curl_getinfo($this->request, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            $code = curl_getinfo($this->request,CURLINFO_HTTP_CODE);
            $error = new exception(curl_error($this->request),curl_errno($this->request),$this->options);
            $this->result = new request_result($code,$header,$body,$error);
            return $this->result;
        } else {
            return $this->result;
        }
    }

    protected function set_default_options()
    {
        curl_setopt($this->request,CURLOPT_HEADER,true);
        curl_setopt($this->request,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($this->request,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->request,CURLOPT_MAXREDIRS,5);
        curl_setopt($this->request,CURLOPT_COOKIEJAR,"./cookie.txt");
        curl_setopt($this->request,CURLOPT_COOKIEFILE,"./cookie.txt");
    }

    public function set_data($pdata)
    {
        if(is_string($pdata))
        {
            $this->posts[] = new post($pdata,post_type::post);
        } else if(is_array($pdata))
        {
            foreach($pdata as $data)
            {
                $this->posts[] = new post($data,post_type::post);
            }
        }

    }

    public function set_form($pdata)
    {
        if(is_string($pdata))
        {
            $this->posts[] = new post($pdata,post_type::form);
        } else if(is_array($pdata))
        {
            foreach($pdata as $data)
            {
                $this->posts[] = new post($data,post_type::form);
            }
        }
    }

    public function set_proxy($pproxy)
    {
        if($pproxy !== false && is_string($pproxy)){
            curl_setopt($this->request, CURLOPT_PROXY, $pproxy);
            //Set Socks5 Proxy id = 7 Socks5 by default
            curl_setopt($this->request, CURLOPT_PROXYTYPE, 7);
        }
    }

    public function set_url($purl)
    {
        if(is_string($purl) && $purl != "")
        {
            curl_setopt($this->request,CURLOPT_URL,$purl);
        }

    }

    public function set_user_agent($puser_agent = "")
    {
        if($puser_agent !== "" && is_string($puser_agent)){
            $this->options["user-agent"] = $puser_agent;
            curl_setopt($this->request,CURLOPT_USERAGENT,$puser_agent);
        } else {
            $this->options["user-agent"] = $_SERVER['HTTP_USER_AGENT'];
            curl_setopt($this->request,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
        }
    }

    public function set_header($pheaders)
    {
        if(is_string($pheaders))
        {
            $new_header = new header($pheaders);
            if(preg_match("~^[\s\t\r]*referer[\s\t\r]*$~im",$new_header->get_key())) $this->set_referer($new_header->get_value());
            elseif(preg_match("~^[\s\t\r]*user-agent[\s\t\r]*$~im",$new_header->get_key())) $this->set_user_agent($new_header->get_value());
            elseif(preg_match("~^[\s\t\r]*cookie[\s\t\r]*$~im",$new_header->get_key())) $this->set_cookie($new_header->get_value());
            else $this->headers[$new_header->get_key()] = $new_header;
        } else if(is_array($pheaders))
        {
            foreach($pheaders as $key => $header)
            {
                $new_header = new header($key.":".$header);
                if(preg_match("~^[\s\t\r]*referer[\s\t\r]*$~im",$new_header->get_key())) $this->set_referer($new_header->get_value());
                elseif(preg_match("~^[\s\t\r]*user-agent[\s\t\r]*$~im",$new_header->get_key())) $this->set_user_agent($new_header->get_value());
                elseif(preg_match("~^[\s\t\r]*cookie[\s\t\r]*$~im",$new_header->get_key())) $this->set_cookie($new_header->get_value());
                else   $this->headers[$new_header->get_key()] = $new_header;

            }
        }

    }

    public function set_max_time($ptimeout)
    {
        $timeout = intval($ptimeout);
        curl_setopt($this->request,CURLOPT_TIMEOUT,$timeout);
    }

    public function set_connect_timeout($ptimeout)
    {
        $timeout = intval($ptimeout);
        curl_setopt($this->request, CURLOPT_CONNECTTIMEOUT, $timeout);
    }

    public function set_custom_request($prequest)
    {
        curl_setopt($this->request,CURLOPT_CUSTOMREQUEST, $prequest);
    }

    public function set_retry($pretry)
    {
        $retry = intval($pretry);
        //To be implemented
    }

    public function set_retry_delay($pretry)
    {
        $retry = intval($pretry);
        //To be implemented
    }

    public function set_retry_max_time($pretry)
    {
        $retry = intval($pretry);
        //To be implemented
    }

    public function set_cookie($pcookies)
    {
        $this->options["cookie"] = $pcookies;
        curl_setopt($this->request,CURLOPT_COOKIE,$pcookies);
    }

    public function set_referer($preferer)
    {
        //Set request Referer
        if($preferer !== "" && is_string($preferer)){
            $this->options["referer"] = $preferer;
            curl_setopt($this->request,CURLOPT_REFERER,$preferer);
        }
    }

    public function set_insecure_mode($pstate)
    {
        if($pstate)
        {
            curl_setopt($this->request, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($this->request, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->request, CURLOPT_SAFE_UPLOAD, TRUE);
        }
    }

    public function get_options()
    {
        return $this->options;
    }

    public function get_option($poption)
    {
        if($poption === "posts") return $this->posts;
        elseif($poption === "headers") return $this->headers;
        elseif(array_key_exists($poption,$this->options)) return $this->options[$poption];
        else return "Undefined";
    }

    public function get_domain()
    {
        if($this->options["url"])
        {
            if(preg_match('~^(?:(?:DICT|FILE|FTPS?|GOPHER|HTTPS?|IMAPS?|LDAPS?|POP3S|RTMP|RTSP|SCP|SFTP|SMBS?|SMTPS?|TELNET|TFTP):\/\/)?(?:\w\w\w\.)?([a-zA-Z0-9][a-zA-Z0-9.-]*[.][a-zA-Z][a-zA-Z]+)(?:[^ ]+)?$~im',$this->options["url"],$domain))
            {
                return $domain[1];
            }
        }
        return "";
    }

    public function get_protocol_handler()
    {
        if($this->options["url"])
        {
            if(preg_match('~^((?:DICT|FILE|FTPS?|GOPHER|HTTPS?|IMAPS?|LDAPS?|POP3S|RTMP|RTSP|SCP|SFTP|SMBS?|SMTPS?|TELNET|TFTP):\/\/)?(?:\w\w\w\.)?(?:[a-zA-Z0-9][a-zA-Z0-9.-]*[.][a-zA-Z][a-zA-Z]+)(?:[^ ]+)?$~im',$this->options["url"],$protocol))
            {
                return $protocol[1];
            }
        }
        return "";
    }

    public function get_gets_parameters()
    {
        if($this->options["url"])
        {
            if(preg_match('~^(?:(?:DICT|FILE|FTPS?|GOPHER|HTTPS?|IMAPS?|LDAPS?|POP3S|RTMP|RTSP|SCP|SFTP|SMBS?|SMTPS?|TELNET|TFTP):\/\/)?(?:\w\w\w\.)?(?:[a-zA-Z0-9][a-zA-Z0-9.-]*[.][a-zA-Z][a-zA-Z]+)(.+)?$~im',$this->options["url"],$gets))
            {
                return $gets[1];
            }
        }
        return "";
    }

}