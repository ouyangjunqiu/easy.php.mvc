<?php
namespace system\redis;
use system\util\App;

/**
 * 基于redis的锁
 *
 */
class Mutex
{

    /**
     * @return \Redis
     */
    public static function getRedis(){
        $redis =  App::getRedis();
        $redis->select(0);
        return $redis;
    }

    /**
     * 获取锁
     * @param  String  $key    锁标识
     * @param  Int     $expire 锁过期时间
     * @return Boolean
     */
    public static function lock($key, $expire=1){
        $is_lock = self::getRedis()->setnx($key, time()+$expire);

        // 不能获取锁
        if(!$is_lock){

            // 判断锁是否过期
            $lock_time = self::getRedis()->get($key);

            // 锁已过期，删除锁，重新获取
            if(time()>$lock_time){
                self::release($key);
                $is_lock = self::getRedis()->setnx($key, time()+$expire);
            }
        }

        return $is_lock? true : false;
    }

    /**
     * @param $key
     * @param int $expire
     * @param int $timeout
     * @return bool
     */
    public static function wait($key, $expire=1 ,$timeout = 60){

        $out_time = time()+$timeout;

        while(time() >= $out_time){

            if(self::lock($key,$expire))
                return true;

            $s = rand(100,500) + 500;
            usleep($s);
        }

        return self::lock($key,$expire);
    }

    /**
     * 释放锁
     * @param  String  $key 锁标识
     * @return Boolean
     */
    public static function release($key){
        return self::getRedis()->del($key);
    }
}
