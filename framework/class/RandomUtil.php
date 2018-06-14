<?php
/**
 * 随机工具
 * 
 * @author Jack Chan
 * @since 2016-05-20
 * @deprecated
 * @see system\util\Random
 */
class RandomUtil
{
	/**
	 * 默认随机数字长度
	 */
	const DEFAULT_RANDOM_NUMBER_LENGTH = 8;
	
	/**
	 * 默认随机字符串长度
	 */
	const DEFAULT_RANDOM_STRING_LENGTH = 16;
	
	/**
	 * 随机字符串字符表，包括字母和数字，字母区分大小写
	 */
	const RANDOM_STRING_CHARS			= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	/**
	 * 随机字符串字符表，包括字母和数字，字母不区分大小写
	 */
	const RANDOM_STRING_CHARS_NOCASE	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	
	/**
	 * 字母随机字符串字符表，区分大小写
	 */
	const RANDOM_STRING_ALPHA			= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	/**
	 * 字母随机字符串字符表，不区分大小写
	 */
	const RANDOM_STRING_ALPHA_NOCASE	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	/**
	 * 字母随机字符串字符表，小写
	 */
	const RANDOM_STRING_LOWER_ALPHA		= 'abcdefghijklmnopqrstuvwxyz';
	
	
	/**
	 * 数字随机字符串字符表
	 */
	const RANDOM_STRING_NUMBER			= '0123456789';
	
	/**
	 * 十六进制随机字符串字符表，字母为大写
	 */
	const RANDOM_STRING_HEX				= '0123456789ABCDEF';
	
	/**
	 * 十六进制随机字符串字符表，字母为小写
	 */
	const RANDOM_STRING_HEX_LOWER		= '0123456789abcdef';
	
	/**
	 * 生成随机整数
	 * @param int $length 随机整数长度
	 * @return int 返回生成的随机整数，如果随机整数长度小于等于0则返回0。
	 */
	public static function createRandomNumber($length = self::DEFAULT_RANDOM_NUMBER_LENGTH)
	{
		if ($length <= 0)
			return 0;
		
		$min = $length > 1 ? pow(10, $length - 1) : 0;
		$max = pow(10, $length) - 1;
		return mt_rand($min, $max);
	}

    /**
     * 生成十六进制随机字符串
     * @param int $length
     * @param bool $lowerCase
     * @return int
     */
	public static function createRandomHex($length = self::DEFAULT_RANDOM_STRING_LENGTH, $lowerCase = false)
	{
		return self::createRandomString($length, $lowerCase ? self::RANDOM_STRING_HEX_LOWER : self::RANDOM_STRING_HEX);
	}
	
	/**
	 * 生成随机字符串
	 * @param int $length 随机字符串长度
	 * @param string $chars 随机字符串字符表
	 * @return int 返回生成的随机字符串，如果随机字符串长度小于等于0则返回null。
	 */
	public static function createRandomString($length = self::DEFAULT_RANDOM_STRING_LENGTH, $chars = self::RANDOM_STRING_CHARS)
	{
		if ($length <= 0)
			return null;
		
		if (!$chars)
			$chars = self::RANDOM_STRING_CHARS;
		
		$str = '';
		$charsCount = strlen($chars);
		
		for ($i = 0; $i < $length; $i++)
			$str .= substr($chars, mt_rand(0, $charsCount - 1), 1);
		
		return $str;
	}
	
	/**
	 * 获取数组随机值
     * @param array $array
	 * @return mixed 返回数组随机值，如果数组为空则返回null。
	 */
	public static function getRandomValue(array $array)
	{
		if (empty($array))
			return null;
		
		$key = mt_rand(0, count($array) - 1);
		return $array[$key];
	}
	
	/**
	 * 通过权重数组获取随机键名，键名根据权重比例返回。
	 * @param array $weightArray 权重数组，键可以为整形也可以为字符串，值为权重值，权重值为整形或浮点型。
	 * @return mixed 返回权重数组键名，如果权重数组为空则返回-1。
	 */
	public static function getRandomKeyByWeight(array $weightArray)
	{
		if (empty($weightArray))
			return -1;
		
		// 总权值
		$allWeight = 0;
		
		// 权重范围数组
		$weightRanges = array();
		
		foreach ($weightArray as $key => $weight)
		{
			$allWeight += $weight;
			$weightRanges[$key] = $allWeight;
		}
		
		// 随机权重
		$randomWeight = mt_rand(0, $allWeight - 1);
		
		// 找到权重范围并返回键名
		foreach ($weightRanges as $key => $weight)
		{
			if ($randomWeight < $weight)
				return $key;
		}
	}
	
	/**
	 * 创建一个32位的uuid
     * @return string
	 */
	public static function createUuid()
	{
		$microTime = microtime();
		list($a_dec, $a_sec) = explode(" ", $microTime);
		$dec_hex = dechex($a_dec* 1000000);
		$sec_hex = dechex($a_sec);
		self::ensure_length($dec_hex, 5);
		self::ensure_length($sec_hex, 6);
		$guid  = "";
		$guid .= $dec_hex;
		$guid .= self::create_guid_section(3);
	
		$guid .= self::create_guid_section(4);
	
		$guid .= self::create_guid_section(4);
	
		$guid .= self::create_guid_section(4);
	
		$guid .= $sec_hex;
		$guid .= self::create_guid_section(6);
		return $guid;
	}

    /**
     * @param $string
     * @param $length
     */
	public static function ensure_length(&$string, $length)
	{
		$strlen = strlen($string);
		if($strlen < $length)
		{
			$string = str_pad($string,$length,"0");
		}
		else if($strlen > $length)
		{
			$string = substr($string, 0, $length);
		}
	}

    /**
     * @param $characters
     * @return string
     */
	public static function create_guid_section($characters)
	{
		$return = "";
		for($i=0; $i<$characters; $i++)
		{
			$return .= dechex(mt_rand(0,15));
		}
		return $return;
	}

    /**
     * 创建密码
     * @param int $length
     * @return string
     */
	public static function createPassword($length = self::DEFAULT_RANDOM_NUMBER_LENGTH)
	{
		$number  	= mt_rand(1,3);
		$bigChar 	= 5 - $number;
		$char		= $length - $number-$bigChar;
		return RandomUtil::createRandomString($bigChar,RandomUtil::RANDOM_STRING_ALPHA_NOCASE).RandomUtil::createRandomString($char,RandomUtil::RANDOM_STRING_LOWER_ALPHA).RandomUtil::createRandomString($number,RandomUtil::RANDOM_STRING_NUMBER);		
	}
	
}