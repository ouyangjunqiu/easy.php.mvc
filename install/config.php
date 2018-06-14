<?php
$path = dirname(__FILE__);
if(!file_exists($path."/install.lock")){
    define('DEBUG',true);
    $config =  require "config.local.php";
    return $config;
}else{
    define('DEBUG',false);
    $key = file_get_contents($path."/install.lock");
    $key = strtolower(trim($key));
    if(empty($key))
        $config =  require "config.ecs.php";
    else
        $config =  require "config.ecs.{$key}.php";
    return $config;
}

