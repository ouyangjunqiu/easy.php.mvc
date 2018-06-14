<?php
namespace system\util;
/**
 * 字符串工具
 * 
 * @author Jack Chan
 * @since 2016-07-01
 */
class StringEx
{
    /**
     * @param $string
     * @param $search
     * @return bool
     */
	public static function startsWith($string, $search)
	{
		return strpos($string, $search) === 0;
	}

    /**
     * @param $string
     * @param $search
     * @return bool
     */
	public static function endsWith($string, $search)
	{
		return strrpos($string, $search) === strlen($string) - strlen($search);
	}

	/**
	 * 检测是否UTF8字符
	 * @param $string
	 * @return boolean
	 */
	public static function isUtf8($string){

		 if(preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",$string)){
			 return true;
		 }
		return false;
	}
}