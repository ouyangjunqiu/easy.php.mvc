<?php
/**
 * 日期时间工具
 * @author Jack Chan
 * @since 2015-12-22
 */
class DateTimeUtil
{
	/**
	 * 获取微秒时间，微秒为百万分之一秒。
	 * @return string 由于超出整型范围，所以只能返回微秒的字符串值
	 */
	public static function getMicroTime()
	{
		$mt = microtime();
		$pair = explode(' ', $mt);
		return $pair[1].substr($pair[0], 2, 6);
	}

    /**
     * 获取当天开始时间，即当天00:00:00。如今天是2015-12-22，获取的时间是2015-12-22 00:00:00对应的时间戳。
     * @param null|string $time
     * @return false|int
     */
	public static function getStartTimeOfDay($time = null)
	{
		return strtotime(date('Y-m-d 00:00:00', $time ? $time : time()));
	}
	
	/**
	 * 获取当天结束时间，即当天23:59:59。如今天是2015-12-22，获取的时间是2015-12-22 23:59:59对应的时间戳。
     * @param null|string $time
     * @return false|int
	 */
	public static function getEndTimeOfDay($time = null)
	{
		return strtotime(date('Y-m-d 23:59:59', $time ? $time : time()));
	}
	
	/**
	 * 通过日期获取当天开始时间
     * @param string $date
     * @return false|int
	 */
	public static function getStartTimeOfDayByDate($date)
	{
		return strtotime(date('Y-m-d 00:00:00', strtotime($date)));
	}

    /**
     * 通过日期获取当天结束时间
     * @param string $date
     * @return false|int
     */
	public static function getEndTimeOfDayByDate($date)
	{
		return strtotime(date('Y-m-d 23:59:59', strtotime($date)));
	}
	
	/**
	 * 获取当月开始时间，即当月第一天00:00:00。如今天是2015-12-22，获取的时间是2015-12-01 00:00:00对应的时间戳。
     * @param mixed $time
     * @return false|int
	 */
	public static function getStartTimeOfMonth($time = null)
	{
		return strtotime(date('Y-m-01 00:00:00', $time ? $time : time()));
	}
	
	/**
	 * 获取当月结束时间，即当月最后一天23:59:59。如今天是2015-12-22，获取的时间是2015-12-31 23:59:59对应的时间戳。
     * @param mixed $time
     * @return false|int
	 */
	public static function getEndTimeOfMonth($time = null)
	{
		return strtotime('+1 month', self::getStartTimeOfMonth($time)) - 1;
	}
}