<?php
/**
 * BUG调试器
 * 
 * @author Jack Chan
 * @since 2016-06-27
 */
class Debug
{
	/**
	 * 调试是否开启
	 */
	protected static $debugEnabled = false;
	
	/**
	 * 开启调试
	 */
	public static function start()
	{
		self::$debugEnabled = true;
	}
	
	/**
	 * 结束调试
     * @param boolean $die
	 */
	public static function end($die = false)
	{
		self::$debugEnabled = false;
		if ($die) die;
	}
	
	/**
	 * 如果开启调试则输出字符串
     * @param string $string
     * @param boolean $die
	 */
	public static function display($string, $die = false)
	{
		if (self::$debugEnabled)
		{
			echo $string;
			if ($die) die;
		}
	}
	
	/**
	 * 如果开启调试则调用print_r
     * @param mixed $var
     * @param boolean $die
	 */
	public static function printR($var, $die = false)
	{
		if (self::$debugEnabled)
		{
			print_r($var);
			if ($die) die;
		}
	}
	
	/**
	 * 如果开启调试则调用var_dump
     * @param mixed $var
     * @param boolean $die
	 */
	public static function dump($var, $die = false)
	{
		if (self::$debugEnabled)
		{
			var_dump($var);
			if ($die) die;
		}
	}
}