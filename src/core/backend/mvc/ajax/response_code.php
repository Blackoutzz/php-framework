<?php
namespace core\backend\mvc\ajax;

/**
 * Ajax Response code
 *
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

abstract class response_code
{

    const successful = 200;
    
    const access_denied = 403;
    
    const invalid_action = 404;
    
    const unexpected_error = 500;

}
