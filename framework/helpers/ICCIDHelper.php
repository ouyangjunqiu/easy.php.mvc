<?php
/**
 * ICCID 生成器，能生成中国移动、中国联通、中国电信
 * ICCIDHelper.php class file
 * @author oShine <oyjqdlp@126.com>
 * @link https://github.com/ouyangjunqiu
 * @copyright 2016-2020 Cloud Bar
 * @since 1.0
 */

namespace system\helpers;


use system\util\Random;

class ICCIDHelper
{
    public static  $mnc = array("00","02","07");
    public static $p = array(
        "01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19",
        "20","21","22","23","24","25","26","27","28","29","30","31"
    );
    public static $year =  array(
        "01", "02", "03","04","05","06","07","08","09","10","11","12","13","14","15","16","17"
    );

    public static function getMNC(){
        $i = mt_rand(0,count(self::$mnc));
        return self::$mnc[$i];
    }

    public static function getP(){
        $i = mt_rand(0,count(self::$p));
        return self::$p[$i];
    }

    public static function getY(){
        $i = mt_rand(0,count(self::$year));
        return self::$year[$i];
    }

    public static function generate(){
        $i = mt_rand(0,2);
        if($i>1){
            return self::CMCC();
        }else if($i>0){
            return self::CUCC();
        }
        return self::CTCC();
    }

    /**
     * 中国移动
     * @return string
     */
    public static function CMCC(){
        $f = "898600,898602";
        $fs = explode(",",$f);
        $i = mt_rand(0,1);

        $no = $fs[$i].self::getMNC().self::getP().self::getY().Random::createRandomNumber(7);
        $l = self::generateLuhn($no);
        return $no.$l;
    }

    /**
     * 中国联通
     * @return string
     */
    public static function CUCC(){
        $f = "898601,898609";
        $fs = explode(",",$f);
        $i = mt_rand(0,1);

        $no = $fs[$i].self::getY().Random::createRandomNumber(1)."0".self::getP().Random::createRandomNumber(7);
        $l = self::generateLuhn($no);
        return $no.$l;
    }

    /**
     * 中国电信
     * @return string
     */
    public static function CTCC(){
        $f = "898603,898606";
        $fs = explode(",",$f);
        $i = mt_rand(0,1);

        $no = $fs[$i]."0".self::getY()."0".self::getP().Random::createRandomNumber(7);
        $l = self::generateLuhn($no);
        return $no.$l;
    }

    public static function generateLuhn($no){
        $no .= "0";
        $arr_no = str_split($no);
        $last_n = $arr_no[count($arr_no)-1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n){
            if($i%2==0){
                $ix = $n*2;
                if($ix>=10){
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                }else{
                    $total += $ix;
                }
            }else{
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $x = 10 - ($total % 10);
        return $x;
    }

}