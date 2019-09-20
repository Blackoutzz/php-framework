<?php
namespace core\backend\network\curl;

define('curl_single_quote_parameter',"\\$?'((?:[^']*|'[^']'[^']'|\\\\\'|'\\\\\'')*)'");
define('curl_double_quote_parameter','\\@?"((?:[^"]*|"[^"]"[^"]"|\\\\\"|"\\\\\"")*)"');

/**
 * Curl Parser
 *
 * Parser to parse command line to php object
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class parser
{

    protected $options = array();

    protected $request;

    public function __construct($preset_options = false)
    {
        if(is_array($preset_options)) $this->options = $preset_options;
        $this->request = new request($this->options);
    }

    public function send()
    {
        $this->request = new request($this->options);
        return $this->request->send();
    }

    public function reset()
    {
        $this->options = array();
        $this->request = new request($this->options);
    }

    public function get_request_from_cmdline($pcurl_cmd)
    {
        $this->reset();
        //Options - https://curl.haxx.se/docs/manpage.html
        try
        {
            if($curl_parameters = preg_replace('~^([\s\t\r]*curl)~im',"",$pcurl_cmd))
            {
                //URL
                if(preg_match('~(?: --url|[^:])[\s\t\r]+(?:\'((?:DICT|FILE|FTPS?|GOPHER|HTTPS?|IMAPS?|LDAPS?|POP3S|RTMP|RTSP|SCP|SFTP|SMBS?|SMTPS?|TELNET|TFTP):\/\/[^\ ]+)\'|"((?:DICT|FILE|FTPS?|GOPHER|HTTPS?|IMAPS?|LDAPS?|POP3S|RTMP|RTSP|SCP|SFTP|SMBS?|SMTPS?|TELNET|TFTP):\/\/[^\ ]+)"|((?:DICT|FILE|FTPS?|GOPHER|HTTPS?|IMAPS?|LDAPS?|POP3S|RTMP|RTSP|SCP|SFTP|SMBS?|SMTPS?|TELNET|TFTP):\/\/[^\ ]+))(?:$|[\s\t\r])~im',$curl_parameters,$url))
                {
                    if(isset($url[1]) && $url[1] != "") $this->options["url"] = $url[1];
                    elseif(isset($url[2]) && $url[2] != "") $this->options["url"] = $url[2];
                    elseif(isset($url[3]) && $url[3] != "") $this->options["url"] = $url[3];
                    else { throw new exception("No url found inside CURL Command : ".$pcurl_cmd); }
                    if($curl_parameters = str_replace(ltrim(rtrim($url[0]),"'".'"'),"",$curl_parameters))
                    {
                        if(preg_match("~( -g| --globoff)(?:$|[\s\t\r]+)~m",$curl_parameters,$globboff))
                        {
                            $this->options["url"] = str_replace(array(']', '['), array('%5D', '%5B'), $this->options["url"]);
                            $curl_parameters = str_replace(rtrim($globboff[1]),"",$curl_parameters);
                        }
                        if(preg_match("~( -i| --include)(?:$|[\s\t\r]+)~m",$curl_parameters,$include))
                        {
                            $curl_parameters = str_replace(rtrim($include[1]),"",$curl_parameters);
                        }
                        if(preg_match("~( -k| --insecure)(?:$|[\s\t\r]+)~m",$curl_parameters,$insecure))
                        {
                            $this->options["insecure"] = true;
                            $curl_parameters = str_replace(rtrim($insecure[1]),"",$curl_parameters);
                        }

                        if(preg_match("~( -s| --silent| -sd)(?:$|[\s\t\r]+)~m",$curl_parameters,$silent))
                        {
                            $this->options["silent"] = true;
                            if($silent[0] === " -sd ") $curl_parameters = str_replace(rtrim($silent[0])," -d ",$curl_parameters);
                            else $curl_parameters = str_replace(rtrim($silent[1]),"",$curl_parameters);
                        }

                        if(preg_match("~(?: -m| --max-time)[\s\t\r]+([0-9]+)(?:$|[\s\t\r]+)~m",$curl_parameters,$maxtime))
                        {
                            $this->options["max-time"] = $maxtime[1];
                            $curl_parameters = str_replace(rtrim($maxtime[0]),"",$curl_parameters);
                        }
                        if(preg_match("~(?: --retry)[\s\t\r]+([0-9]+)(?:$|[\s\t\r]+)~m",$curl_parameters,$retry))
                        {
                            $this->options["retry"] = $retry[1];
                            $curl_parameters = str_replace(rtrim($retry[0]),"",$curl_parameters);
                        }
                        if(preg_match("~(?: --retry-max-time)[\s\t\r]+([0-9]+)(?:$|[\s\t\r]+)~m",$curl_parameters,$retry))
                        {
                            $this->options["retry"] = $retry[1];
                            $curl_parameters = str_replace(rtrim($retry[0]),"",$curl_parameters);
                        }
                        if(preg_match("~(?: --retry-delay)[\s\t\r]+([0-9]+)(?:$|[\s\t\r]+)~m",$curl_parameters,$retry))
                        {
                            $this->options["retry"] = $retry[1];
                            $curl_parameters = str_replace(rtrim($retry[0]),"",$curl_parameters);
                        }
                        if(preg_match("~(?: --connect-timeout)[\s\t\r]+([0-9]+)(?:$|[\s\t\r]+)~m",$curl_parameters,$connecttimeout))
                        {
                            $this->options["connect-timeout"] = $connecttimeout[1];
                            $curl_parameters = str_replace(rtrim($connecttimeout[0]),"",$curl_parameters);
                        }
                        if(preg_match('~(?: -X| --request)[\s\t\r]+(?:(POST|GET|HEAD)|'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.'|([^\s\t\r]+))(?:$|[\s\t\r])~m',$curl_parameters,$requestmethod))
                        {
                            if(isset($requestmethod[1]) && $requestmethod[1] != "") $this->options["request"] = $requestmethod[1];
                            elseif(isset($requestmethod[2]) && $requestmethod[2] != "") $this->options["request"] = $requestmethod[2];
                            elseif(isset($requestmethod[3]) && $requestmethod[3] != "") $this->options["request"] = $requestmethod[3];
                            elseif(isset($requestmethod[4]) && $requestmethod[4] != "") $this->options["request"] = $requestmethod[4];
                            else { throw new exception("No request method found to dump headers inside CURL Command : ".$pcurl_cmd); }
                            $curl_parameters = str_replace(rtrim($requestmethod[0]),"",$curl_parameters);
                        }
                        if(preg_match('~(?: -D| --dump-header)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.'|(\-))(?:$|[\s\t\r])~m',$curl_parameters,$dumpheader))
                        {
                            if(isset($dumpheader[1]) && $dumpheader[1] != "") $this->options["dump-header"] = $dumpheader[1];
                            elseif(isset($dumpheader[2]) && $dumpheader[2] != "") $this->options["dump-header"] = $dumpheader[2];
                            elseif(isset($dumpheader[3]) && $dumpheader[3] != "") $this->options["dump-header"] = $dumpheader[3];
                            else { throw new exception("No file found to dump headers inside CURL Command : ".$pcurl_cmd); }
                            $curl_parameters = str_replace(rtrim($dumpheader[0]),"",$curl_parameters);
                        }
                        if(preg_match('~(?: -A| --user-agent)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')(?:$|[\s\t\r])~m',$curl_parameters,$user_agent))
                        {
                            if(isset($user_agent[1]) && $user_agent[1] != "") $this->options["user-agent"] = $user_agent[1];
                            elseif(isset($user_agent[2]) && $user_agent[2] != "") $this->options["user-agent"] = $user_agent[2];
                            elseif(isset($user_agent[1]) && $user_agent[1] == "") $this->options["user-agent"] = "";
                            elseif(isset($user_agent[2]) && $user_agent[2] == "") $this->options["user-agent"] = "";
                            else { throw new exception("No User-Agent found inside CURL Command : ".$pcurl_cmd); }
                            $curl_parameters = str_replace(rtrim($user_agent[0]),"",$curl_parameters);
                        }
                        if(preg_match('~(?: -x| --proxy)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')(?:$|[\s\t\r])~m',$curl_parameters,$proxy))
                        {
                            if(isset($proxy[1]) && $proxy[1] != "") $this->options["proxy"] = $proxy[1];
                            elseif(isset($proxy[2]) && $proxy[2] != "") $this->options["proxy"] = $proxy[2];
                            else { throw new exception("No Proxy found inside CURL Command : ".$pcurl_cmd); }
                            $curl_parameters = str_replace(rtrim($proxy[0]),"",$curl_parameters);
                        }
                        if(preg_match('~(?: -X| --request)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')(?:$|[\s\t\r])~m',$curl_parameters,$request))
                        {
                            if(isset($request[1]) && $request[1] != "") $this->options["request"] = $request[1];
                            elseif(isset($request[2]) && $request[2] != "") $this->options["request"] = $request[2];
                            else { throw new exception("No Request-Method found inside CURL Command : ".$pcurl_cmd); }
                            $curl_parameters = str_replace(rtrim($request[0]),"",$curl_parameters);
                        }
                        if(preg_match('~(?: -e| --referer)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')(?:$|[\s\t\r])~m',$curl_parameters,$referer))
                        {
                            if(isset($referer[1]) && $referer[1] != "") $this->options["referer"] = $referer[1];
                            elseif(isset($referer[2]) && $referer[2] != "") $this->options["referer"] = $referer[2];
                            elseif(isset($referer[1]) && $referer[1] == "") $this->options["referer"] = "";
                            elseif(isset($referer[2]) && $referer[2] == "") $this->options["referer"] = "";
                            else { throw new exception("No Referer found inside CURL Command : ".$pcurl_cmd); }
                            $curl_parameters = str_replace(rtrim($referer[0]),"",$curl_parameters);
                        }
                        if(preg_match('~(?: -b| --cookie)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')~m',$curl_parameters,$cookie))
                        {
                            if(isset($cookie[1]) && $cookie[1] != "") $this->options["cookie"] = $cookie[1];
                            elseif(isset($cookie[2]) && $cookie[2] != "") $this->options["cookie"] = $cookie[2];
                            else { throw new exception("No Cookie found inside CURL Command : ".$pcurl_cmd); }
                            $curl_parameters = str_replace(rtrim($cookie[0]),"",$curl_parameters);
                        }

                        if(preg_match_all('~(?: -d| --data| --data-binary| --data-ascii | --data-raw| --data-urlencode)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')(?:$|[\s\t\r])~m',$curl_parameters,$data))
                        {
                            $datas = array();
                            if(is_array($data[1]) && count($data[1]) >= 1)
                            {
                                foreach($data[1] as $new_data)
                                {
                                    if($new_data != "") $datas[] = $new_data;
                                }
                            }
                            if(is_array($data[2]) && count($data[2]) >= 1)
                            {
                                foreach($data[2] as $new_data)
                                {
                                    if($new_data != "") $datas[] = $new_data;
                                }
                            }
                            if(count($data[1]) < 1 && count($data[2]) < 1) throw new exception("No Data found inside CURL Command : ".$pcurl_cmd);
                            foreach($data[0] as $removed_data) $curl_parameters = str_replace(rtrim($removed_data),"",$curl_parameters);
                            $this->options["data"] = $datas;
                        }

                        if(preg_match_all('~(?: -F| --form| --form-string)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')~m',$curl_parameters,$form))
                        {
                            $forms = array();
                            if(is_array($form[1]) && count($form[1]) >= 1)
                            {
                                foreach($form[1] as $new_form)
                                {
                                    if($new_form != "") $forms[] = $new_form;
                                }
                            }
                            if(is_array($form[2]) && count($form[2]) >= 1)
                            {
                                foreach($form[2] as $new_form)
                                {
                                    if($new_form != "") $forms[] = $new_form;
                                }
                            }
                            if(count($form[1]) < 1 && count($form[2]) < 1) throw new exception("No Form found inside CURL Command : ".$pcurl_cmd);
                            foreach($form[0] as $removed_form) $curl_parameters = str_replace(rtrim($removed_form),"",$curl_parameters);
                            $this->options["form"] = $forms;
                        }

                        if(preg_match_all('~(?: -H| --header)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')~m',$curl_parameters,$header))
                        {
                            $headers = array();
                            if(is_array($header[1]) && count($header[1]) >= 1)
                            {
                                foreach($header[1] as $new_header)
                                {
                                    if($new_header != "") $headers[] = $new_header;
                                }
                            }
                            if(is_array($header[2]) && count($header[2]) >= 1)
                            {
                                foreach($header[2] as $new_header)
                                {
                                    if($new_header != "") $headers[] = $new_header;
                                }
                            }
                            if(count($header[1]) < 1 && count($header[2]) < 1) throw new exception("No Header found inside CURL Command : ".$pcurl_cmd);
                            foreach($header[0] as $removed_header) $curl_parameters = str_replace(rtrim($removed_header),"",$curl_parameters);
                            $this->options["header"] = $headers;
                            if(!isset($this->options["max-time"])) $this->options["max-time"] = 3;
                            if(!isset($this->options["connect-timeout"])) $this->options["connect-timeout"] = 3;
                        }

                        if(preg_match_all('~^(?: -d| --data| --data-binary| --data-ascii | --data-raw| --data-urlencode)[\s\t\r]+(?:'.curl_double_quote_parameter.'|'.curl_single_quote_parameter.')$~m',$curl_parameters,$data))
                        {

                            $datas = array();
                            if(is_array($data[1]) && count($data[1]) >= 1)
                            {
                                foreach($data[1] as $new_data)
                                {
                                    if($new_data != "") $datas[] = $new_data;
                                }
                            }
                            if(is_array($data[2]) && count($data[2]) >= 1)
                            {
                                foreach($data[2] as $new_data)
                                {
                                    if($new_data != "") $datas[] = $new_data;
                                }
                            }
                            if(count($data[1]) < 1 && count($data[2]) < 1) throw new exception("No Data found inside CURL Command : ".$pcurl_cmd);
                            foreach($data[0] as $removed_data) $curl_parameters = str_replace(rtrim($removed_data),"",$curl_parameters);
                            if(count($this->options["data"]) >=1)
                            {
                                $this->options["data"] = array_merge($this->options["data"],$datas);
                            } else {
                                $this->options["data"] = $datas;
                            }
                        }
                        if(trim($curl_parameters) != "") throw new exception("Unexpected parameters inside CURL Command : ".$curl_parameters);
                    }
                } else {
                    throw new exception("No URL found on CURL Command : ".$pcurl_cmd);
                }
            } else {
                throw new exception("No Curl found on CURL Command : ".$pcurl_cmd);
            }
        }
        catch (exception $e)
        {
            return new request($this->options);
        }

        return new request($this->options);
    }

}