<?php
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL^E_NOTICE^E_DEPRECATED);
date_default_timezone_set('PRC');

defined("BASE_PATH") or define("BASE_PATH",__DIR__);
defined("DATA_PATH") or define("DATA_PATH",__DIR__."/data");

$config = require_once('./install/config.php');
require_once('./framework/ClassLoader.php');

\system\util\App::loadConfig($config);
defined('BEGIN_TIME') OR define('BEGIN_TIME',time());
if(defined('DEBUG') && DEBUG) Debug::start();

Controller::runController();
