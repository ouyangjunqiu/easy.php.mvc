<?php
/**
 * TimeHelper.php class file
 * @author oShine <oyjqdlp@126.com>
 * @link https://github.com/ouyangjunqiu
 * @copyright 2016-2020 Cloud Bar
 * @since 1.0
 */

namespace system\helpers;


class TimeHelper
{
    public static function format($seconds){
        $seconds = (int)$seconds;
        if( $seconds>=24*3600){
            $days_num = "";
            if( $seconds>24*3600 ){
                $days		= (int)($seconds/86400);
                $days_num	= $days."天";
                $seconds	= $seconds%86400;//取余
            }
            $hours = intval($seconds/3600);
            $minutes = $seconds%3600;//取余下秒数
            $time = $days_num.$hours."时".gmstrftime('%M分%S秒', $minutes);
        }else if($seconds>=3600){
            $time = gmstrftime('%H时%M分%S秒', $seconds);
        }else if($seconds>=60){
            $time = gmstrftime('%M分%S秒', $seconds);
        }else{
            $time = gmstrftime('%S秒', $seconds);
        }
        return $time;
    }

}