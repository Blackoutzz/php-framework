#!/bin/bash
php /var/www/html/index.php docker-setup
/usr/sbin/apache2ctl -D FOREGROUND