<?php
/**
 * AllowIp class file
 * @author oShine <oyjqdlp@126.com>
 * @link https://github.com/ouyangjunqiu
 * @copyright 2016-2020 Cloud Bar
 */

namespace system\acl;


class AllowIp
{
    protected static $setAllowIp = array(
        '192.178.8.1', '192.178.5.6'
    );

    /**
     * 获取真实IP
     * @return string 返回ip
     */
    public static function getRealIp()
    {
        if (!isset($_SERVER['REMOTE_ADDR'])) {
            return 'unknow';
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        if (getenv('HTTP_CLIENT_IP')) {
            $clientIp = getenv('HTTP_CLIENT_IP');
            $matcheClientIp = preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $clientIp);
            if ($matcheClientIp) {
                $ip = $clientIp;
            }
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] as $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        $ip = ($ip == '::1') ? '127.0.0.1' : $ip;
        return $ip;
    }


    /**
     * 检查当前IP
     * @param $allowIp
     * @return bool|null
     */
    public static function checkAccessIp($allowIp)
    {
        if (empty($allowIp)) {
            header('HTTP/1.1 403 Forbidden');
            echo "Access forbidden";
            exit;
        }

        //获取ip地址
        $ip = self::getRealIp();

        //要检测的ip拆分成数组
        $check_ip_arr = explode('.', $ip);

        //如果ip不在白名单数组中
        if (!in_array($ip, $allowIp)) {
            foreach ($allowIp as $val) {
                //如果白名单中存在范围ip
                if (strpos($val, '-') !== false) {
                    $range = explode('-', $val);
                    //用于记录循环检测中是否有匹配成功的标志位
                    $result = self::inIpRange($ip, $range[0], $range[1]);

                    if ($result) {
                        return true;
                    }
                }
                //发现白名单有*号替代符
                if (strpos($val, '*') !== false) {
                    $arr = array();
                    //将白名单ip地址拆分
                    $arr = explode('.', $val);
                    //用于记录循环检测中是否有匹配成功的标志位
                    $flag = true;
                    for ($i = 0; $i < 4; $i++) {
                        //不等于*  就要进来检测，如果为*符号替代符就不检查
                        if ($arr[$i] != '*') {
                            if ($arr[$i] != $check_ip_arr[$i]) {
                                $flag = false;
                                break;//终止检查本个ip 继续检查下一个ip
                            }
                        }
                    }
                    //如果是true则找到有一个匹配成功的就返回
                    if ($flag) {
                        return true;
                    }
                }

            }
            header('HTTP/1.1 403 Forbidden');
            echo "Access forbidden";
            exit;
        }
    }

    /**
     * 判断是否在ip范围内
     * @param string $ip
     * @param string $ipOne
     * @param string $ipTwo
     * @return boolean
     */
    public static function inIpRange($ip, $ipOne, $ipTwo)
    {
        return ip2long($ipOne) * -1 >= ip2long($ip) * -1 && ip2long($ipTwo) * -1 <= ip2long($ip) * -1;
    }

}