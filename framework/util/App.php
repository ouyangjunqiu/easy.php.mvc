<?php
/**
 * App.php
 * @author oShine <jqouyang@system.co>
 * @since 2017/4/16 15:27
 */

namespace system\util;


use system\db\Connection;
use Redis;

class App
{
    /**
     * @var array
     */
    private static $config = array();

    /**
     * @var array
     */
    private static $components= array();

    /**
     * 加载配置文件
     * @param $config
     */
    public static function loadConfig($config)
    {
        self::$config = $config;
    }

    /**
     * 获取配置文件的内容
     * @param $key
     * @return mixed
     */
    public static function getConfig($key)
    {
        return self::$config[$key];
    }

    /**
     * 获取所有的数据库配置文件
     * @return mixed
     */
    public static function getDatabasesConfig()
    {
        return self::getConfig("databases");
    }

    /**
     * 获取单个数据库的配置文件
     * @param string $key
     * @return mixed
     */
    public static function getDbConfig($key = "db")
    {
        $databases = self::getDatabasesConfig();
        if (isset($databases[$key])) {
            return $databases[$key];
        }
        return array(
            "host" => "127.0.0.1",
            "user" => "root",
            "password" => "123456",
            "database" => "wx_qrcode",
            "port" => 3306,
            "prefix" => 'sj_',
            "charset" => 'utf8'
        );
    }

    /**
     * 获取Redis的配置文件
     * @return mixed
     */
    public static function getRedisConfig(){
        return self::getConfig("redis");
    }

    /**
     * 实例化Redis
     * @return Redis
     */
    public static function getRedis(){

        $config = self::getRedisConfig();
        if(empty(self::$components["redis"]) || !self::$components["redis"] instanceof Redis){
            self::$components["redis"] = new Redis();
            self::$components["redis"]->pconnect($config["host"],$config["port"]);
        }
        try {
            $ping = self::$components["redis"]->ping();
            if (!preg_match('/PONG/', $ping)) {
                self::$components["redis"]->pconnect($config["host"],$config["port"]);
            }
        }catch (\Exception $e){
            self::$components["redis"]->pconnect($config["host"],$config["port"]);
        }
        return self::$components["redis"];
    }

    /**
     * 实例化Connection
     * @param string $key
     * @return Connection
     * @see self::getDb()
     * @deprecated
     */
    public static function getDbConnection($key = "db"){
        return self::getDb($key);
    }

    /**
     * 实例化Connection
     * @param string $key
     * @return Connection
     */
    public static function getDb($key = "db"){
        if(empty(self::$components[$key]) || !self::$components[$key] instanceof Connection){
            $config = self::getDbConfig($key);
            self::$components[$key] = new Connection($config["host"], $config["port"], $config["user"], $config["password"], $config["database"], $config["charset"], $config["prefix"]);
        }
        return self::$components[$key];
    }

    /**
     * @return mixed
     */
    public static function getName(){
        return self::getConfig('name');
    }

    public static function getRuntimePath()
    {
        return self::getConfig('runtime');
    }

}