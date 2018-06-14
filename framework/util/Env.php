<?php
namespace system\util;

/**
 * 获取环境信息
 * @author oShine
 * Date: 2017/3/29
 * Time: 10:11
 */
class Env
{
    /**
     * 获取客户端的IP地址
     */
    public static function getClientIp(){
        if ( !isset( $_SERVER['REMOTE_ADDR'] ) ) {
            return 'unknow';
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        if ( getenv( 'HTTP_CLIENT_IP' ) ) {
            $clientIp = getenv( 'HTTP_CLIENT_IP' );
            $matcheClientIp = preg_match( '/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $clientIp );
            if ( $matcheClientIp ) {
                $ip = $clientIp;
            }
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && preg_match_all( '#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches ) ) {
            foreach ( $matches[0] as $xip ) {
                if ( !preg_match( '#^(10|172\.16|192\.168)\.#', $xip ) ) {
                    $ip = $xip;
                    break;
                }
            }
        }
        $ip = ($ip == '::1') ? '127.0.0.1' : $ip;
        return $ip;
    }

    /**
     * 获取用户的Agent
     * @return mixed
     */
    public static function getUerAgent(){
        return $_SERVER["HTTP_USER_AGENT"];
    }

    /**
     * 获取用户平台
     * @return string
     */
    public static function getPlatform(){
        $userAgent = Env::getUerAgent();
        if(preg_match("/iPhone/",$userAgent)){
            return "iPhone";
        }

        if(preg_match("/Android/",$userAgent)){
            return "Android";
        }

        return "Web";
    }

}