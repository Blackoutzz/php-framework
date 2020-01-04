<?php
require('vendor/autoload.php');
require('main.php');
if(isset($db) && isset($salt) && isset($algo)) $argv = [];
$main = new framework\main($argv);
