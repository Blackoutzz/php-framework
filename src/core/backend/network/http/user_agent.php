<?php
namespace core\backend\network\http;

/**
 * user_agent short summary.
 *
 * user_agent description.
 *
 * @version 1.0
 * @author la_ma
 **/

abstract class user_agent
{

    const google_chrome = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36";

    const mozilla_firefox = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:50.0) Gecko/20100101 Firefox/50.0";

    const github = "Blackoutzz-Php-Lib";

    const curl = "curl/7.19.7 (i386-redhat-linux-gnu) libcurl/7.19.7 NSS/3.27.1 zlib/1.2.3 libidn/1.18 libssh2/1.4.2";

    static function get_from_user()
    {
        return htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
    }

}
