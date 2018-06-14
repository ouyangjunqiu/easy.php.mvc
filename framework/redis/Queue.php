<?php
namespace system\redis;
use system\util\App;
use Redis;

/**
 * Queue class file
 * @author oShine <oyjqdlp@126.com>
 * @link https://github.com/ouyangjunqiu
 * @copyright 2016-2020 Cloud Bar
 */
class Queue
{
    /**
     * 默认主机名
     */
    const DEFAULT_HOST		= '127.0.0.1';

    /**
     * 默认端口
     */
    const DEFAULT_PORT		= '6379';

    /**
     * 队列名称
     * @var string $qsName
     */
    private $qsName = "";


    /**
     * RedisQueue constructor.
     * @param string $qsName
     */
    public function __construct($qsName = 'qs.default')
    {
        $this->qsName = $qsName;
    }

    /**
     * @return Redis
     */
    public static function getRedis(){
        $redis =  App::getRedis();
        $redis->select(0);
        return $redis;
    }

    /**
     * 入队列
     * @param $value
     * @return int
     */
    public function push($value){
        return self::getRedis()->rPush($this->qsName,serialize($value));
    }

    /**
     * 出列
     * @return mixed
     */
    public function pop(){
        return unserialize(self::getRedis()->lPop($this->qsName));
    }

    /**
     * 队头入列
     * @param $value
     * @return int
     */
    public function lPush($value){
        return self::getRedis()->lPush($this->qsName,serialize($value));
    }

    /**
     * 队尾出列
     * @return mixed
     */
    public function rPop(){
        return unserialize(self::getRedis()->rPop($this->qsName));
    }

}