<?php
return array(
    "databases" => array(
        "db" => array(
            "host" => "127.0.0.1",
            "user" => "root",
            "password" => "",
            "database" => "scwxdb",
            "port" => 3306,
            "prefix" => '',
            "charset" => 'utf8'
        ),
    ),
    "redis" => array(
        "host" => '127.0.0.1',
        "port" => 6379
    ),
    'name' => '这是一个简单的mvc应用',
    'runtime' => dirname(dirname(__FILE__))."/data"
);